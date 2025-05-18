<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeePhone;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lastname', 'dni', 'cuil', 'email', 'position', 'is_represent', 'company_id'];

      //Relación 1:n atributo multivaluado en la tabla Contract
      public function contracts(): HasMany
      {
          return $this->hasMany(Contract::class, 'contact_employee_id');
      }

       //Relación 0:n atributo multivaluado en la tabla Contract(representative_employee)
       public function representativeContracts(): HasMany
       {
           return $this->hasMany(Contract::class, 'representative_employee_id');
       }


    // Define la relación uno a muchos con EmployeePhone
    public function phones()
    {
        return $this->hasMany(EmployeePhone::class);
    }

    // Relación inversa con Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
