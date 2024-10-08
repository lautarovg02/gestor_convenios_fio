<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePhone extends Model
{
    use HasFactory;

    protected $fillable = ['phone_numb', 'employee_id'];

    // Define la relación inversa con Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
