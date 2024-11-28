<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherSearchTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test that the search returns teachers matching the search term.
     * @test
     */
    public function search_returns_matching_teachers()
    {
        // Arrange: Create teachers
        $matchingTeacher = Teacher::factory()->create(['name' => 'John', 'lastname' => 'Doe', 'dni' => '12345678']);
        $nonMatchingTeacher = Teacher::factory()->create(['name' => 'Jane', 'lastname' => 'Smith']);

        // Act: Perform a search
        $response = $this->get(route('teachers.index', ['search' => 'John']));

        // Assert: Check that only the matching teacher is displayed
        $response->assertStatus(200);
        $response->assertSee('John');
        $response->assertSee('Doe');
        $response->assertDontSee('Jane');
        $response->assertDontSee('Smith');
    }

    /**
     * Test that the search handles no results gracefully.
     * @test
     */
    public function search_displays_empty_message_when_no_results()
    {
        // Arrange: Ensure the database is empty
        Teacher::factory()->create(['name' => 'Unrelated Name']);

        // Act: Perform a search that yields no results
        $response = $this->get(route('teachers.index', ['search' => 'Nonexistent']));

        // Assert: Check that the empty message is displayed
        $response->assertStatus(200);
        $response->assertSee('No se han encontrado Docentes');
    }

    /**
     * Test that the search returns results for multiple fields (e.g., name, lastname, dni, or cuil).
     * @test
     */
    public function search_returns_results_based_on_multiple_fields()
    {
        // Arrange: Create teachers with varying attributes
        $teacherByName = Teacher::factory()->create(['name' => 'Alice']);
        $teacherByLastName = Teacher::factory()->create(['lastname' => 'Johnson']);
        $teacherByDNI = Teacher::factory()->create(['dni' => '87654321']);
        $teacherByCUIL = Teacher::factory()->create(['cuil' => '20-12345678-9']);

        // Act: Perform searches for each field
        $responseName = $this->get(route('teachers.index', ['search' => 'Alice']));
        $responseLastName = $this->get(route('teachers.index', ['search' => 'Johnson']));
        $responseDNI = $this->get(route('teachers.index', ['search' => '87654321']));
        $responseCUIL = $this->get(route('teachers.index', ['search' => '20-12345678-9']));

        // Assert: Check that each search term retrieves the correct teacher
        $responseName->assertStatus(200)->assertSee('Alice');
        $responseLastName->assertStatus(200)->assertSee('Johnson');
        $responseDNI->assertStatus(200)->assertSee('87654321');
        $responseCUIL->assertStatus(200)->assertSee('20-12345678-9');
    }
}
