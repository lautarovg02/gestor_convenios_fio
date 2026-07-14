<?php

namespace App\Services;

use App\Models\Employee;
use Faker\Factory as Faker;

class EmployeeService
{
    protected $faker;
    protected $companyService;
    protected $employeePhoneService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
        $this->faker = Faker::create();
        $this->employeePhoneService = new EmployeePhoneService();
    }

    /**
     * Busca un empleado por DNI y si no existe lo crea.
     *
     * @param array $data Datos para crear o buscar el empleado. Debe incluir 'dni' obligatoriamente.
     * @return Employee
     */
    public function findOrCreateByDni(array $data): Employee
    {

        if (!$data['cuil'])
            $employee = Employee::where('dni', $data['dni'])->first();
        else
            $employee = Employee::where('dni', $data['dni'])->orWhere('cuil', $data['cuil'])->first();


        if ($employee) {
            return $employee;
        }

        // Si no existe, crear
        $nuevoEmpleado = Employee::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'dni' => $data['dni'],
            'cuil' => $data['cuil'] ?? null,
            'email' => $data['email'],
            'position' => $data['position'],
            'is_represent' => $data['is_represent'],
            'company_id' => $data['company_id'],
        ]);

        if ($data['phone']) {
            $this->employeePhoneService->createNumberPhone([
                'employee_id' => $nuevoEmpleado->id,
                'number' => $data['phone'],
            ]);
        }

        return $nuevoEmpleado;
    }
}
