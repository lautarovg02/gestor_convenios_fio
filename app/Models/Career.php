<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Career extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'department_id'];

    public function teachers():BelongsToMany
    {
        return  $this->belongsToMany(Teacher::class, 'career_teacher');
    }
}
