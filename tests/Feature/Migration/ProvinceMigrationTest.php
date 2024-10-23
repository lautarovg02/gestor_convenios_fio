<?php

namespace Tests\Feature\Migration;

use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProvinceMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Test to ensure the 'provinces' table is created.
    *
    @test
    @dairagalceran
    */
    public function test_it_creates_provinces_table()
    {
        // Check if  'provinces' table exists
        if (Schema::hasTable('provinces')) {
                $this->assertTrue(true, 'Test passed: The provinces table was successfully created.');
            } else {
                $this->assertTrue(false, 'Test failed: The provinces table was not created.');
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
        $province = new Province();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the city with null values
        $province->save();
    }

    /**
        * Test to ensure required columns are present in the 'provinces' table.
        *
        @test
        @dairagalceran
    */
    public function test_it_has_required_columns_in_provinces_table()
    {
        // Get the list of columns in the 'provinces' table
        $columns = Schema::getColumnListing('provinces');

        // Check for the presence of required columns: 'id', 'name'
            $this->assertTrue(Schema::hasColumn('provinces', 'id'), 'Test failed: The id column was not created.');
            $this->assertTrue(Schema::hasColumn('provinces', 'name'), 'Test failed: The name column was not created.');
    }
}
