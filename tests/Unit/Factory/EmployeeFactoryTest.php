<?php

namespace Tests\Unit\Factory;

use App\Models\City;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Test\TestCase;
use Tests\TestCase as TestsTestCase;

class EmployeeFactoryTest extends TestsTestCase
{
    use RefreshDatabase;

    /**
     * Test that the factory creates a Employee with valid attributes.
        @dairagalceran
        @test
     */
    public function test_employee_factory_creates_valid_company()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        //Crear empleado
        $employee = Employee::factory()->create();


        $this->assertNotNull($employee);
        $this->assertDatabaseHas('employees', [
            'name' => $employee->name,
            'lastname' => $employee->lastname,
            'dni' => $employee->dni,
            'cuil' => $employee->cuil,
            'email' => $employee->email,
            'position' => $employee->position,
            'is_represent' => $employee->is_represent,
            'company_id' => $employee->company_id,
        ]);
    }

    /**
     * Test that the factory generates unique dni.
        @dairagalceran
        @test
     */
    public function test_factory_generates_unique_dni()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        Employee::factory()->create(['dni' => '23415500']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Employee::factory()->create(['dni' => '23415500']);
    }

    /**
     * Test that the factory generates unique cuil.
        @dairagalceran
        @test
     */
    public function test_factory_generates_unique_cuil()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        Employee::factory()->create(['cuil' => '27234155001']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Employee::factory()->create(['cuil' => '27234155001']);
    }

    /**
     * Test that the factory generates unique email.
        @dairagalceran
        @test
     */
    public function test_factory_generates_unique_email()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        Employee::factory()->create(['email' => 'test@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Employee::factory()->create(['email' => 'test@example.com']);
    }

    /**
     * Test that the factory generates unique cuil with exactly 11 digits.
        @dairagalceran
        @test
     */
    public function test_company_factory_generates_valid_cuil_with_eleven_digits()
    {

        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();$employee = Employee::factory()->create();
        $cuil = $employee->cuil;

        // Verificar que el CUIL tiene exactamente 11 caracteres
        $this->assertEquals(11, strlen($cuil));

        // Verificar que el CUIL es numérico
        $this->assertMatchesRegularExpression('/^\d{11}$/', $cuil, "El CUIL debe tener exactamente 11 dígitos.");
    }

    /**
     * Test that the factory has all necessary attributes defined.
        @dairagalceran
        @test
     */
    public function test_company_factory_has_required_attributes()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        $employee = Employee::factory()->make();

        $this->assertNotNull($employee->name);
        $this->assertNotNull($employee->lastname);
        $this->assertNotNull($employee->dni);
        $this->assertNotNull($employee->position);
        $this->assertNotNull($employee->is_represent);
        $this->assertNotNull($employee->company_id);
    }
}
