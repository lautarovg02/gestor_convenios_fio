<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Career;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;


    /**
     * Roles disponibles para los docentes.
     */
    const ROLE_DIRECTOR = 'Director';
    const ROLE_COORDINATOR = 'Coordinador';
    const ROLE_NONE = 'Sin rol';

    /**
     * Lista completa de roles disponibles.
     *
     * @var array
     */
    public const AVAILABLE_ROLES = [
        self::ROLE_DIRECTOR,
        self::ROLE_COORDINATOR,
        self::ROLE_NONE,
    ];

    protected $fillable = [
        'lastname',
        'name',
        'dni',
        'cuil',
        'teacher_id',
        'is_rector',
        'is_dean',
    ];

    //Relación 1:n atributo multivaluado en la tabla Teacher
    public function cathedras(): HasMany
    {
        return $this->hasMany(Cathedra::class, 'teacher_id');
    }

    //Relación n:n con tabla Teacher
    public function careers(): BelongsToMany
    {
        return $this->belongsToMany(Career::class, 'career_teacher');
    }

    /** Relación con Department (uno a uno)
     * Obtener el department del cual el teacher es director de departemento.
     */
    public function department(): HasOne
    {
        return  $this->hasOne(Department::class);
    }

    /** Relación con Career (uno a uno)
     * Obtener la career del cual el teacher es coordinador.
     */
    public function career(): HasOne
    {
        return $this->hasOne(Career::class);
    }

    /**
     * Recuperar todos los profesores con sus roles.
     * Este método realiza una consulta para obtener todos los profesores, incluyendo información sobre si
     * son directores de un departamento o coordinadores de una carrera.
     * Si un profesor es director, se incluye la información del departamento, y si un profesor es
     * coordinador, se incluye la información de la carrera.
     * Los profesores que no son ni directores ni coordinadores tendrán el rol 'Ninguno'.
     *
     * @return \Illuminate\Support\Collection Lista de profesores con sus roles e información adicional.
     * @lautarovg02
     */
    public static function getAllWithRoles($searchTerm = null)
    {
        return self::select(
            'teachers.*',
            'departments.name as department_name',
            'departments.id as department_id',
            'careers.name as career_name',
            'careers.id as career_id',
            \DB::raw("CASE
                        WHEN departments.id IS NOT NULL THEN 'Director'
                        WHEN careers.id IS NOT NULL THEN 'Coordinador'
                        ELSE 'Sin rol'
                      END as role")
        )
            ->leftJoin('departments', 'teachers.id', '=', 'departments.director_id')
            ->leftJoin('careers', 'teachers.id', '=', 'careers.coordinator_id')
            ->distinct(); // Asegura que los resultados no se dupliquen
    }

    /**
     * Scope para filtrar los profesores según la carrera.
     *
     * Este método aplica un filtro a la consulta para obtener los profesores
     * que están asociados a una carrera específica. Verifica tanto si el profesor
     * es coordinador de la carrera como si pertenece a ella a través de la tabla
     * intermedia `career_teacher`.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query La consulta actual de Eloquent.
     * @param int|null $careerId El ID de la carrera para aplicar el filtro.
     * @return \Illuminate\Database\Eloquent\Builder La consulta filtrada.
     *
      @lautarovg02
     */
    public static function scopeFilterByCareer($query, $careerId)
    {
        if (!empty($careerId)) {
            $query->where(function ($subQuery) use ($careerId) {
                $subQuery->where('careers.id', $careerId)
                    ->orWhereHas('careers', function ($q) use ($careerId) {
                        $q->where('career_teacher.career_id', $careerId);
                    });
            });
        }
        return $query;
    }

    /**
     * Verifica si el docente tiene el rol de Director de un Departamento.
     *
     * @return bool Retorna verdadero si el docente es director de un departamento, falso en caso contrario.
     * @lautarovg02
     */
    public function hasDirectorRole(): bool
    {
        return Department::where('director_id', $this->id)->exists();
    }

    /**
     * Verifica si el docente tiene el rol de Coordinador de una Carrera.
     *
     * @return bool Retorna verdadero si el docente es coordinador de una carrera, falso en caso contrario.
     * @lautarovg02
     */
    public function hasCoordinatorRole(): bool
    {
        return Career::where('coordinator_id', $this->id)->exists();
    }

    /**
     * Verifica si el docente tiene algún rol asociado (Director o Coordinador).
     *
     * @return bool Retorna verdadero si el docente tiene algún rol asociado, falso en caso contrario.
     * @lautarovg02
     */
    public function hasAnyRole(): bool
    {
        return $this->hasDirectorRole() || $this->hasCoordinatorRole();
    }


    /**
     * Obtiene el nombre del rol que tiene el docente, en caso de tener uno.
     *
     * @return string Retorna una descripción del rol del docente o "Sin rol asignado" si no tiene ninguno.
     * @lautarovg02
     */
    public function getRoleName(): string
    {
        if ($this->hasDirectorRole()) {
            return 'Director de Departamento';
        }

        if ($this->hasCoordinatorRole()) {
            return 'Coordinador de Carrera';
        }

        if ($this->is_dean) {
            return 'Decano';
        }

        if ($this->is_rector) {
            return 'Rector';
        }

        return 'Sin rol asignado';
    }
    /**
     * Recuperar todos los docentes que no sean directores de ningún departamento
     * ni coordinadores de ninguna carrera, ni rectores, ni decanos.
     *
     * Este método realiza una consulta para obtener todos los docentes que no tengan
     * roles de directores, coordinadores, rectores o decanos.
     *
     * @return \Illuminate\Support\Collection List of teachers without roles.
     */
    public static function getTeachersWithoutRoles()
    {
        return self::whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                ->from('careers')
                ->whereColumn('careers.coordinator_id', 'teachers.id');
        })
            ->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('departments')
                    ->whereColumn('departments.director_id', 'teachers.id');
            })
            ->where(function ($query) {
                $query->where('is_rector', 0)
                      ->orWhereNull('is_rector');
            })
            ->where(function ($query) {
                $query->where('is_dean', 0)
                      ->orWhereNull('is_dean');
            });
    }

    /**
     * @dairagalceran
     * Scope for search in teachers.index
     */
    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                // Buscar en el nombre del docente
                $query->where('teachers.name', 'LIKE', '%' . $searchTerm . '%')
                    // Buscar en el apellido del docente
                    ->orWhere('teachers.lastname', 'LIKE', '%' . $searchTerm . '%')
                    // Buscar en el CUIL del docente
                    ->orWhere('teachers.cuil', 'LIKE', '%' . $searchTerm . '%')
                    // Buscar en el dni del docente
                    ->orWhere('teachers.dni', 'LIKE', '%' . $searchTerm . '%');
            });
        }
    }

    /**
     * Obtener un docente específico con su información relacionada (roles, departamento, carrera).
     * Este método realiza una consulta para devolver un único docente junto con la información de
     * si es director de un departamento o coordinador de una carrera.
     *
     * @param int $teacherId El ID del docente que se desea buscar.
     * @return \Illuminate\Database\Eloquent\Model|null El docente con la información asociada o null si no existe.
     * @lautarovg02
     */
    public static function getTeacherWithRoles($teacherId)
    {
        return self::select(
            'teachers.*',
            'departments.name as department_name',
            'departments.id as department_id',
            'careers.name as career_name',
            'careers.id as career_id',
            \DB::raw("CASE
                    WHEN departments.id IS NOT NULL THEN 'Director'
                    WHEN careers.id IS NOT NULL THEN 'Coordinador'
                    ELSE 'None'
                END as role")
        )
            ->leftJoin('departments', 'teachers.id', '=', 'departments.director_id')
            ->leftJoin('careers', 'teachers.id', '=', 'careers.coordinator_id')
            ->where('teachers.id', $teacherId)
            ->first(); // Retornar el primer resultado o null si no existe.
    }

    /**
     * Obtener las carreras relacionadas con un docente específico y determinar si es coordinador o profesor.
     *
     * Este método devuelve el nombre del docente, apellido, nombre de la carrera,
     * y una columna adicional que indica si el docente es "Coordinador" o "Profesor".
     *
     * @param int $teacherId El ID del docente.
     * @return \Illuminate\Support\Collection Resultado de la consulta con la relación del docente y sus carreras.
     * @lautarovg02
     */
    public static function getTeacherWithRelationToCareers(int $teacherId)
    {
        return self::select(
            'teachers.name',
            'teachers.lastname',
            'careers.name as career',
            \DB::raw("CASE
                        WHEN teachers.id = careers.coordinator_id THEN 'Coordinador'
                        ELSE 'Profesor'
                      END as relation")
        )
            ->join('career_teacher', 'teachers.id', '=', 'career_teacher.teacher_id')
            ->join('careers', 'career_teacher.career_id', '=', 'careers.id')
            ->where('teachers.id', $teacherId)
            ->get();
    }
}
