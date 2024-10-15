<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'director_id'];


    /** Relación con Teacher (opcional) director_id
     *  Obtener el teacher asociado al department si no es null.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'director_id');
    }

    /** Relación con Career 1..*
     * Obtener las careers que  pertenecen al department.
     */
    public function careers():HasMany
    {
        return $this->hasMany(Career::class);
    }
}
