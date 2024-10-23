<?php

namespace Tests\Feature\Migration;

use App\Models\City;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CityMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Test to ensure the 'secretaries' table is created.
    *
    @test
    @dairagalceran
    */
    public function test_it_creates_cities_table()
    {
        // Check if  'cities' table exists
        if (Schema::hasTable('cities')) {
                $this->assertTrue(true, 'Test passed: The cities table was successfully created.');
            } else {
                $this->assertTrue(false, 'Test failed: The cities table was not created.');
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
        // Attempt to create a city with null values for name and province_id
        Province::factory()->create();
        $city = new City();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the city with null values
        $city->save();
    }

    /**
        * Test to ensure required columns are present in the 'cities' table.
        *
        @test
        @dairagalceran
    */
    public function test_it_has_required_columns_in_cities_table()
    {
        // Get the list of columns in the 'cities' table
        $columns = Schema::getColumnListing('cities');

        // Check for the presence of required columns: 'id', 'name', 'province_id'
            $this->assertTrue(Schema::hasColumn('cities', 'id'), 'Test failed: The id column was not created.');
            $this->assertTrue(Schema::hasColumn('cities', 'name'), 'Test failed: The name column was not created.');
            $this->assertTrue(Schema::hasColumn('cities', 'province_id'), 'Test failed: The province_id column was not created.');
    }

}
