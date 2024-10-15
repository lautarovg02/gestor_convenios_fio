<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Career;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['lastname' , 'name' , 'dni', 'faculty' , 'teacher_id'];

    //Relación 1:n atributo multivaluado en la tabla Teacher
    public function cathedras():HasMany
    {
        return $this->hasMany(Cathedra::class , 'teacher_id');
    }

    //Relación n:n con tabla Teacher
    public function careers():BelongsToMany
    {
        return $this->belongsToMany(Career::class, 'career_teacher');

    }

    /** Relación con Department (uno a uno)
     * Obtener el department del cual el teacher es director de departemento.
     */
    public function department():HasOne
    {
        return  $this->hasOne(Department::class);
    }

    /** Relación con Career (uno a uno)
     * Obtener la career del cual el teacher es coordinador.
     */
    public function career():HasOne
    {
        return $this->hasOne(Career::class);
    }
}
