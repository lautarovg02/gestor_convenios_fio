<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Career extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'coordinator_id', 'department_id'];

    public function teachers():BelongsToMany
    {
        return  $this->belongsToMany(Teacher::class, 'career_teacher')->withTimestamps();
    }

    /** Relación con Teacher (opcional) coordinador_id
     *  Obtener el teacher asociado a la career si no es null.
     */
    public function teacher():BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'coordinator_id');
    }

    /** Relación con Department *..1
     * Obtener el department al que pertenece la career.
     */
    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
