<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class StudentMigrationTest extends TestCase
{
    use RefreshDatabase;

 /** @test */
    public function test_it_creates_student_table()
    {
        // Check if  'students' table exists.
        if (Schema::hasTable('students')) {
                $this->assertTrue(true, 'Test passed: The student table was successfully created.');
            } else {
                $this->assertTrue(false, 'Test failed: The student table was not created.');
            }
    }

    /** @test */
    public function  test_it_has_required_columns_in_students_table()
    {
        
        // Verify that the 'students' table has the database fields.
        $this->assertTrue(\Schema::hasColumns('students', [
            'id',
            'name',
            'last_name',
            'dni',
            'cuil',
            'email',
            'phone_numb',
            'career',
        ]));
    }


/** @test */

public function test_unique_fields_cannot_be_duplicated()
{
    $studentData = [
        'name' => 'Juan',
        'last_name' => 'Pérez',
        'dni' => 12345678,
        'cuil'=> 23331007009,
        'email' => 'juan@example.com',
        'phone_numb' => 1234567890,
    ];

    // Create the first student
    \App\Models\Student::create($studentData);

    // Expect an exception when trying to create a duplicate
    $this->expectException(\Illuminate\Database\QueryException::class);

     // Try to create a duplicate student
    \App\Models\Student::create($studentData);
}


/** @test */
public function test_it_does_not_accept_null_values_on_students()
{
    $this->expectException(\Illuminate\Database\QueryException::class);

    // Create an instance of the model without the required fields
    $student = new \App\Models\Student();

    //Try to save the model without required data
    $student->save();
}
















}
