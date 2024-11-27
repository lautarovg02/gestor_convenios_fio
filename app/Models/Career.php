<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Career extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coordinator_id', 'department_id'];

    public function teachers(): BelongsToMany
    {
        return  $this->belongsToMany(Teacher::class, 'career_teacher')->withTimestamps();
    }

    /** Relación con Teacher (opcional) coordinador_id
     *  Obtener el teacher asociado a la career si no es null.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'coordinator_id');
    }


    public function scopeSearchAndSort($query,  $departmentId, $search = null, $sort = 'name', $direction = 'asc')
    {
        // Asegúrate de que la dirección sea válida
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Aplicar el filtro por departamento
        $query->when($departmentId, function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        });

        // Aplicar la búsqueda
        return $query->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('department', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
        })->orderBy($sort, $direction);
    }

    /** Relación con Department *..1
     * Obtener el department al que pertenece la career.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
