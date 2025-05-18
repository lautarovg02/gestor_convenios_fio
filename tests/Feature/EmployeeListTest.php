<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Company;
use App\Models\CompanyEntity;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeListTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function displays_the_list_of_employees_for_a_company(): void
    {
        City::factory()->create();
        CompanyEntity::factory()->create();
        $company = Company::factory()->create();
        $employee = Employee::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->get(route('companies.employees.index', ['company' => $company->id]));

        $response->assertStatus(200);
        $response->assertViewIs('employees.index');
        $response->assertViewHas('employees', function ($employees) use ($employee) {
            return $employees->contains($employee);
        });

        $response->assertSee($employee->name);
        $response->assertSee($employee->last_name);
        $response->assertSee($employee->dni);
        $response->assertSee($employee->email);
        $response->assertSee($employee->position);
        $response->assertSee((string) $employee->is_representative);
    }

}
