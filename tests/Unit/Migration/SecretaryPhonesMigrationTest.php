<?php

namespace Tests\Unit;

use App\Models\SecretaryPhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class SecretaryPhonesMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to ensure the 'secretaries_phones' table is created
     @test
     @lautarovg02
     */
    public function test_it_creates_secretaries_phones_table()
    {
        // Check if the secretaries_phones table exists
        $this->assertTrue(Schema::hasTable('secretaries_phones'), 'Test failed: secretaries_phones table was not created');
        $this->assertTrue(Schema::hasTable('secretaries_phones'), 'Test passed: secretaries_phones table was not created');
    }

     /**
     * Test to ensure that required fields do not accept null values.
     *
      @test
      @lautarovg02
     */
    public function test_it_does_not_accept_null_values()
    {
        // Attempt to create a secretary with null values for phone_number and id_Secretary
        $secretaryPhone = new SecretaryPhone();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the secretaryPhone with null values
        $secretaryPhone->save();
    }

    /**
     * Test to ensure required columns are present in the 'secretaries_phones' table.
     @test
     @lautarovg02
     */
    public function test_it_has_required_columns_in_secretaries_phones_tables()
    {
        // Get the list of colums in the 'secretaries_phones' table
        $columns = Schema::getColumnListing('secretaries_phones');

        // Check for the presence of required columns
        $this->assertTrue(in_array('phone_number', $columns), 'Test failed: Column phone_number is missing.');

        // Check for the presence of required columns
        $this->assertTrue(in_array('secretary_id', $columns), 'Test failed: Column secretary_id is missing.');

         // Check for the presence of required columns
         $this->assertTrue(in_array('phone_number', $columns), 'Test passed: Column phone_number is present.');

         // Check for the presence of required columns
         $this->assertTrue(in_array('secretary_id', $columns), 'Test failed: Column secretary_id is present.');
    }
}
