<?php

namespace Tests\Unit\Factory;

use App\Models\Employee;
use App\Models\EmployeePhone;
use PHPUnit\Framework\TestCase;

class EmployeePhoneFactoryTest extends TestCase
{
    /**
     * Test that the factory creates a Employee with valid attributes.
        @dairagalceran
        @test
     */
    public function test_employee_factory_creates_valid_company(){

        Employee::factory()->create(); //error al no reconocer faker->firstName
        $employee_phone = EmployeePhone::factory()->create();

        $this->assertNotNull($employee_phone);
        /* $this->assertDatabaseHas('employee_phones', [
            'number' => $employee_phone->number,
            'employee_id' => $employee_phone->employee_id,
        ]); */
    }

}
