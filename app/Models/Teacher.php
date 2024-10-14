<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Career;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
