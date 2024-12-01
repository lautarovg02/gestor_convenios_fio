<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @author rom-L
     * Test that a department without associated careers can be deleted successfully.
     */
    public function test_department_can_be_deleted_if_no_careers_are_associated()
    {
        // Arrange: Create a teacher and assign as the department's director
        $teacher = Teacher::factory()->create();
        $department = Department::factory()->create(['director_id' => $teacher->id]);

        // Act: Attempt to delete the department
        $response = $this->delete(route('departments.destroy', $department));

        // Assert: The department is deleted and a success message is displayed
        $response->assertRedirect(route('departments.index'))
                 ->assertSessionHas('success', 'Departamento eliminado exitosamente.');

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }

    /**
     * @test
     * @author rom-L
     * Test that a department with associated careers cannot be deleted.
     */
    public function test_department_cannot_be_deleted_if_careers_are_associated()
    {
        // Arrange: Create a teacher and assign as the department's director
        $teacher = Teacher::factory()->create();
        $department = Department::factory()->create(['director_id' => $teacher->id]);

        // Associate a career with the department
        Career::factory()->create(['department_id' => $department->id]);

        // Act: Attempt to delete the department
        $response = $this->delete(route('departments.destroy', $department));

        // Assert: The department still exists and an error message is displayed
        $response->assertRedirect(route('departments.index'))
                 ->assertSessionHas('error', 'No se puede eliminar el departamento porque tiene carreras asociadas.');

        $this->assertDatabaseHas('departments', ['id' => $department->id]);
    }
}
