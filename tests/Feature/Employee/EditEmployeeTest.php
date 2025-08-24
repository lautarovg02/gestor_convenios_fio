<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;
    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();

        $this->employee = Employee::factory()->create([
            'company_id' => $this->company->id,
            'name'       => 'Ana',
            'lastname'   => 'Pérez',
            'dni'        => '35555555',
            'cuil'       => '27-35555555-3',
            'position'   => 'Responsable Comercial',
            'email'      => 'ana.perez@acme.com',
        ]);
    }

    /** @test */
    public function edita_un_empleado_correctamente()
    {
        $payload = [
            'name'     => 'Ana',
            'lastname' => 'Pérez',
            'dni'      => '35555555',
            'cuil'     => '27-35555555-3',
            'position' => 'Jefa Comercial',
            'email'    => 'ana.p@acme.com',
        ];

        $response = $this->put(
            route('employees.update', $this->employee), 
            $payload
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('employees', [
            'id'       => $this->employee->id,
            'position' => 'Jefa Comercial',
            'email'    => 'ana.p@acme.com',
        ]);
    }

    /** @test */
    public function requiere_campos_obligatorios_al_editar()
    {
        $payload = [
            'name'     => '',
            'lastname' => '',
            'dni'      => '',
            'position' => '',
        ];

        $response = $this->put(
            route('employees.update', $this->employee),
            $payload
        );

        $response->assertSessionHasErrors([
            'name',
            'lastname',
            'dni',
            'position',
        ]);
    }

    /** @test */
    public function valida_dni_unico_al_editar()
    {
        // Creamos otro empleado con un DNI distinto
        $other = Employee::factory()->create([
            'company_id' => $this->company->id,
            'dni'        => '12345678',
        ]);

        $payload = [
            'name'     => 'Ana',
            'lastname' => 'Pérez',
            'dni'      => '12345678', // duplicado del otro
            'position' => 'Responsable Comercial',
        ];

        $response = $this->put(
            route('employees.update', $this->employee),
            $payload
        );

        $response->assertSessionHasErrors(['dni']);

        // Aseguramos que el DNI del original no cambió
        $this->assertDatabaseHas('employees', [
            'id'  => $this->employee->id,
            'dni' => '35555555', // sigue siendo el original
        ]);
    }

    /** @test */
    public function permite_mantener_el_mismo_dni_al_editar()
    {
        $payload = [
            'name'     => 'Ana',
            'lastname' => 'Pérez',
            'dni'      => '35555555', // mismo DNI del propio empleado
            'position' => 'Gerente',
        ];

        $response = $this->put(
            route('employees.update', $this->employee),
            $payload
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('employees', [
            'id'       => $this->employee->id,
            'position' => 'Gerente',
            'dni'      => '35555555', // no cambió
        ]);
    }
}
