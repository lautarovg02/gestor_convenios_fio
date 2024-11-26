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

    protected $fillable = ['lastname', 'name', 'dni', 'faculty', 'teacher_id'];

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
                        ELSE 'None'
                    END as role")
        )
            ->leftJoin('departments', 'teachers.id', '=', 'departments.director_id')
            ->leftJoin('careers', 'teachers.id', '=', 'careers.coordinator_id');
    }
    /**
     * Recuperar todos los docentes que no sean directores de ningún departamento
     * ni coordinadores de ninguna carrera.
     *
     * Este método realiza una consulta para obtener todos los docentes que no tengan
     * roles de directores o coordinadores.
     *
     * @return \Illuminate\Support\Collection List of teachers without roles.
     *@lautarovg02
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
}
