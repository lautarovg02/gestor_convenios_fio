<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeEditAndUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_displays_employee_data()
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        $response = $this->get(route('employees.edit', $employee->id));

        $response->assertStatus(200);
        $response->assertViewIs('employees.edit');
        $response->assertSee('value="' . e($employee->name) . '"', false);
        $response->assertSee('value="' . e($employee->lastname) . '"', false);
        $response->assertSee('value="' . e($employee->email) . '"', false);
        $response->assertSee('value="' . e($employee->dni) . '"', false);
    }

    public function test_update_employee_successfully()
    {
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);



        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $updatedData = [
            'name' => 'NuevoNombre',
            'lastname' => 'NuevoApellido',
            'email' => 'nuevo@email.com',
            'dni' => '12345678',
            'position' => 'Analista',
            'is_represent' => true
        ];

        $response = $this->patch(route('employees.update', $employee->id), $updatedData);

        $response->assertRedirect(route('companies.employees.index', $company->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'NuevoNombre',
            'lastname' => 'NuevoApellido',
        ]);
    }
 
    public function test_update_employee_validation_error_preserves_old_input()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);
    
        $invalidData = [
            'name' => '',
            'lastname' => '',
            'email' => 'no-es-email',
            'dni' => '',
        ];
    
        $response = $this->from(route('employees.edit', $employee->id))
            ->patch(route('employees.update', $employee->id), $invalidData);
    
        $response->assertRedirect(route('employees.edit', $employee->id));
        $response->assertSessionHasErrors(['name', 'lastname', 'dni']);
        $response->assertSessionHas('_old_input'); 
    }
    
}
