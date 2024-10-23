<?php

namespace Tests\Feature\Migration;

use App\Models\City;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeePhone;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeePhoneMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Test to ensure the 'employees' table is created.
    *
    @test
    @dairagalceran
    */
    public function test_it_creates_employee_phones_table()
    {
        // Check if the 'employee_phones' table exists
        if (Schema::hasTable('employee_phones')) {
                $this->assertTrue(true, 'Test passed: The employeePhones table was successfully created.');
            } else {
                $this->assertTrue(false, 'Test failed: The employeesPhones table was not created.');
            }
    }

    /**
     * Test to ensure that required fields do not accept null values.
     *
    @test
    @dairagalceran
    */
    public function test_it_does_not_accept_null_values()
    {
        // Attempt to create an employee_phone with null values for number and employee_id
        $employee_phones = new EmployeePhone();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the secretary with null values
        $employee_phones->save();
    }

    /**
    * Test to ensure required columns are present in the 'employee_phones' table.
    *
    @test
    @dairagalceran
    */
    public function test_it_has_required_columns_in_employee_phones_table()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        Employee::factory()->create();

        // Get the list of columns in the 'companies' table
        $columns = Schema::getColumnListing('employee_phones');

        // Check for the presence of required columns: 'id', 'name', 'lastname','dni', 'position', 'is_represent', 'company_id'
        $this->assertTrue(Schema::hasColumn('employee_phones', 'id'), 'Test failed: The id column was not created.');
        $this->assertTrue(Schema::hasColumn('employee_phones', 'number'), 'Test failed: The number column was not created.');
        $this->assertTrue(Schema::hasColumn('employee_phones', 'employee_id'), 'Test failed: The employee_id column was not created.');
    }

}
