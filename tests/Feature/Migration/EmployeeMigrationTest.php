<?php

namespace Tests\Feature\Migration;

use App\Models\City;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Province;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Test to ensure the 'employees' table is created.
    *
    @test
    @dairagalceran
    */
    public function test_it_creates_employes_table()
    {
        // Check if the 'employee' table exists
        if (Schema::hasTable('employees')) {
                $this->assertTrue(true, 'Test passed: The employees table was successfully created.');
            } else {
                $this->assertTrue(false, 'Test failed: The employees table was not created.');
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
        // Attempt to create an employee with null values for user_name, email, and password
        $employee = new Employee();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the secretary with null values
        $employee->save();
    }

    /**
        * Test to ensure required columns are present in the 'employees' table
        @test
        @dairagalceran
    */
    public function test_it_has_required_columns_in_employees_table()
    {
        // Get the list of columns in the 'companies' table
        $columns = Schema::getColumnListing('employees');

        // Check for the presence of required columns: 'id', 'name', 'lastname','dni', 'position', 'is_represent', 'company_id'
            $this->assertTrue(Schema::hasColumn('employees', 'id'), 'Test failed: The id column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'name'), 'Test failed: The name column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'lastname'), 'Test failed: The lastname column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'dni'), 'Test failed: The dni column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'cuil'), 'Test failed: The cuil  column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'position'), 'Test failed: The position column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'is_represent'), 'Test failed: The is_represent column was not created.');
            $this->assertTrue(Schema::hasColumn('employees', 'company_id'), 'Test failed: The company_id column was not created.');
    }


    /**
    * Test to ensure that 'dni', 'cuil' and 'email' are unique.
    *
    @test
    @dairagalceran
    */
    public function test_it_ensures_cuil_dni_and_email_are_unique()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create();
        // Create an employee  with unique 'dni', 'cuil' and 'email

        Employee::factory()->create([
            'dni' => '10050000',
            'cuil' => '27100000002',
            'email' => 'test@example.com',
        ]);

        // Try to create another employee with the same 'dni', 'cuil' and 'email
        try {
            Employee::factory()->create([
                'dni' => '10050000',    // Duplicate
                'cuil' => '27100000002',    // Duplicate
                'email' => 'test@example.com', // Duplicate
            ]);
            // If we reach this point, the test failed as it should have thrown an exception
            $this->fail('Test failed: A QueryException was expected due to duplicate entries, but none was thrown.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Exception was thrown, which means the test passed
            $this->assertTrue(true, 'Test passed: A QueryException was correctly thrown for duplicate entries.');
        }
    }

}
