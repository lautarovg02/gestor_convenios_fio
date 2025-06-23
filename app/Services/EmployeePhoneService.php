<?php

namespace App\Services;

use App\Models\EmployeePhone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmployeePhoneService
{
    /**
     * Crea un teléfono para un empleado.
     *
     * @param array $data
     * @return EmployeePhone
     * @throws ValidationException
     */

    public function createNumberPhone(array $data): EmployeePhone
    {
        return EmployeePhone::create([
            'employee_id' => $data['employee_id'],
            'number' => $data['number'],
        ]);
    }
}
