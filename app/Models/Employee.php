<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeePhone;
class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lastname', 'dni', 'cuil' , 'email' , 'position', 'is_represent','company_id'];

       // Define la relación uno a muchos con EmployeePhone
    public function phones()
    {
        return $this->hasMany(EmployeePhone::class);
    }
}
