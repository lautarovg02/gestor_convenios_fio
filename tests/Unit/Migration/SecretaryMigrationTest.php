<?php

namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Database\Factories\SecretaryFactory;
use App\Models\Secretary;

class SecretaryMigrationTest extends TestCase
{
    use RefreshDatabase;
    /**
    * Test to ensure the 'secretaries' table is created.
    *
     @test
     @lautarovg02
    */
   public function test_it_creates_secretaries_table()
   {
       // Check if the 'secretaries' table exists
       $this->assertTrue(Schema::hasTable('secretaries'), 'Test failed: The secretaries table was not created.');
       $this->assertTrue(Schema::hasTable('secretaries'), 'Test passed: The secretaries table was successfully created.');
   }

     /**
     * Test to ensure that required fields do not accept null values.
     *
      @test
      @lautarovg02
     */
    public function test_it_does_not_accept_null_values()
    {
        // Attempt to create a secretary with null values for user_name, email, and password
        $secretary = new Secretary();

        // Assert that we receive validation errors
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Attempt to save the secretary with null values
        $secretary->save();
    }

   /**
    * Test to ensure required columns are present in the 'secretaries' table.
    *
     @test
     @lautarovg02
    */
   public function test_it_has_required_columns_in_secretaries_table()
   {
       // Get the list of columns in the 'secretaries' table
       $columns = Schema::getColumnListing('secretaries');

       // Check for the presence of required columns
       $this->assertTrue(in_array('user_name', $columns), 'Test failed: Column user_name is missing.');
       $this->assertTrue(in_array('email', $columns), 'Test failed: Column email is missing.');
       $this->assertTrue(in_array('password', $columns), 'Test failed: Column password is missing.');

       $this->assertTrue(in_array('user_name', $columns), 'Test passed: Column user_name is present.');
       $this->assertTrue(in_array('email', $columns), 'Test passed: Column email is present.');
       $this->assertTrue(in_array('password', $columns), 'Test passed: Column password is present.');
   }

   /**
    * Test to ensure that 'user_name' and 'email' are unique.
    *
    @test
    @lautarovg02
    */
   public function test_it_ensures_user_name_and_email_are_unique()
   {
       // Create a secretary with unique email and user_name
       Secretary::factory()->create([
           'user_name' => 'unique_username',
           'email' => 'unique@example.com',
       ]);

       // Try to create another secretary with the same user_name and email
       try {
            Secretary::factory()->create([
               'user_name' => 'unique_username', // Duplicate
               'email' => 'unique@example.com',  // Duplicate
           ]);
           // If we reach this point, the test failed as it should have thrown an exception
           $this->fail('Test failed: A QueryException was expected due to duplicate entries, but none was thrown.');
       } catch (\Illuminate\Database\QueryException $e) {
           // Exception was thrown, which means the test passed
           $this->assertTrue(true, 'Test passed: A QueryException was correctly thrown for duplicate entries.');
       }
   }
}
