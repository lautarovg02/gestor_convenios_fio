<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Cathedra extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' , 'teacher_id'];

     //Relación  1:n atributo multivaluado
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class , 'teacher_id');
    }
}
