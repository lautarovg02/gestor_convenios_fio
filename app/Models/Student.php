<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'last_name', 'dni','cuil','email','phone_numb','career'];

     //Relación n:n con tabla Teacher
     public function teachers(): BelongsToMany
        {
            return $this->belongsToMany(Teacher::class, 'teacher_tutor_student');
        }
    public function individualInternshipAgreements()
    {
        return $this->hasMany(IndividualInternshipAgreement::class);
    }
    public function specificResidenceAgreements()
    {
        return $this->hasMany(SpecificResidenceAgreement::class);
    }
}
