<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Session;
use Tests\TestCase;

class DepartmentsIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the department index page displays departments and their directors.
     @rom-L
     @test
     */
    public function index_displays_departments_and_directors()
    {
        // Arrange: Create test data
        $teacher = Teacher::factory()->create(['name' => 'John', 'lastname' => 'Doe']);
        $department = Department::factory()->create([
            'name' => 'Computer Science',
            'director_id' => $teacher->id,
        ]);

        // Act: Visit the department index page
        $response = $this->get(route('departments.index'));

        // Assert: Check if data is displayed correctly
        $response->assertStatus(200);
        $response->assertSee('Computer Science');
        $response->assertSee('Doe John'); // Full name of the director
    }

    /**
     * Test that the department index page handles empty results gracefully.
     @rom-L
     @test
     */
    public function index_displays_empty_message_when_no_departments_exist()
    {
        // Act: Visit the department index page without creating any data
        $response = $this->get(route('departments.index'));

        // Assert: Check for a specific empty state message
        $response->assertStatus(200);
        $response->assertSee('No se han encontrado departamentos');
    }

    /**
     * Test that the success message is displayed when present in the session.
     @rom-L
     @test
     */
    public function displays_success_message()
    {
        // Arrange: Set a success message in the session
        Session::flash('success', 'Operación realizada con éxito.');

        // Act: Visit the department index page
        $response = $this->get(route('departments.index'));

        // Assert: Check that the success message is displayed
        $response->assertStatus(200);
        $response->assertSee('Operación realizada con éxito.');
    }

    /**
     * Test that the error message is displayed when present in the session.
     @rom-L
     @test
     */
    public function displays_error_message()
    {
        // Arrange: Set an error message in the session
        Session::flash('error', 'Hubo un problema al realizar la operación.');

        // Act: Visit the department index page
        $response = $this->get(route('departments.index'));

        // Assert: Check that the error message is displayed
        $response->assertStatus(200);
        $response->assertSee('Hubo un problema al realizar la operación.');
    }
}
