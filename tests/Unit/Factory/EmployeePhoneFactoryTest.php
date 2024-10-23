<?php

namespace Tests\Unit\Factory;

use App\Models\City;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeePhone;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeePhoneFactoryTest extends TestCase
{

    use RefreshDatabase;
    /**
     * Test that the factory creates a Employee with valid attributes.
        @dairagalceran
        @test
     */
    public function test_employee_factory_creates_valid_company(){
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        Employee::factory()->create(); //error al no reconocer faker->firstName
        $employee_phone = EmployeePhone::factory()->create();

        $this->assertNotNull($employee_phone);
        $this->assertDatabaseHas('employee_phones', [
            'number' => $employee_phone->number,
            'employee_id' => $employee_phone->employee_id,
        ]);
    }

}
