<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CareerDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful career deletion.
     * @rom-L
     * @test
     */
    public function test_career_deletion_success()
    {
        Teacher::factory()->count(10)->create();
        Department::factory()->create();

        // Arrange: Create a career instance
        $career = Career::factory()->create();

        // Act: Perform a delete request
        $response = $this->delete(route('careers.destroy', $career));

        // Assert: Redirect to index with success message
        $response->assertRedirect(route('careers.index'))
                 ->assertSessionHas('success', 'Carrera eliminada exitosamente.');

        // Assert: The career is removed from the database
        $this->assertDatabaseMissing('careers', ['id' => $career->id]);
    }

    /**
     * Test career deletion with non-existent ID.
     * @rom-L
     * @test
     */
    public function test_career_deletion_with_non_existent_id()
    {
        // Act: Attempt to delete a non-existent career
        $response = $this->delete(route('careers.destroy', 999));

        // Assert: Check for a 404 response
        $response->assertNotFound();
    }
}
