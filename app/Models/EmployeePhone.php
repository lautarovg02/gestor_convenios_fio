<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePhone extends Model
{
    use HasFactory;
    protected $table = 'employee_phones';
    public $timestamps = false;

    protected $fillable = ['number', 'employee_id'];

    // Define la relación inversa con Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
