<?php

namespace Tests\Feature\Migration;

use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CompanyMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Test to ensure the 'secretaries' table is created.
    *
    @test
    @dairagalceran
    */
    public function test_it_creates_companies_table()
    {
        // Check if the 'company' table exists
        if (Schema::hasTable('companies')) {
                $this->assertTrue(true, 'Test passed: The companies table was successfully created.');
            } else {
                $this->assertTrue(false, 'Test failed: The companies table was not created.');
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
        // Attempt to create a secretary with null values for user_name, email, and password
        $company = new Company();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the secretary with null values
        $company->save();
    }

        /**
            * Test to ensure required columns are present in the 'companies' table.
            *
            @test
            @dairagalceran
            */
    public function test_it_has_required_columns_in_companies_table()
    {
        // Get the list of columns in the 'companies' table
        $columns = Schema::getColumnListing('companies');

        // Check for the presence of required columns: 'id', 'denomination', 'city_id'
            $this->assertTrue(Schema::hasColumn('companies', 'id'), 'Test failed: The id column was not created.');
            $this->assertTrue(Schema::hasColumn('companies', 'denomination'), 'Test failed: The denomination column was not created.');
            $this->assertTrue(Schema::hasColumn('companies', 'city_id'), 'Test failed: The city_id column was not created.');
    }

    /**
    * Test to ensure that 'user_name' and 'email' are unique.
    *
    @test
    @dairagalceran
    */
    public function test_it_ensures_cuit_and_company_name_are_unique()
    {
        // Create a company with unique email and user_name
        Province::factory()->Create();
        City::factory()->create();
        Company::factory()->create([
            'company_name' => 'unique_company_name',
            'cuit' => '27100000002',
        ]);

        // Try to create another secretary with the same user_name and email
        try {
            Province::factory()->Create();
            City::factory()->create();
            Company::factory()->create([
                'company_name' => 'unique_company_name', // Duplicate
                'cuit' => '27100000002', // Duplicate
            ]);
            // If we reach this point, the test failed as it should have thrown an exception
            $this->fail('Test failed: A QueryException was expected due to duplicate entries, but none was thrown.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Exception was thrown, which means the test passed
            $this->assertTrue(true, 'Test passed: A QueryException was correctly thrown for duplicate entries.');
        }
    }


}

