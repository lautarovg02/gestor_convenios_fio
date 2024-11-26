<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Career;
use App\Models\Teacher;
use App\Models\Department;



class CareerCreateFormTest extends TestCase
{
    use RefreshDatabase;

    /**
    @test
    @lautarvg02
     * Test that the career creation form shows only teachers eligible
     * to be coordinators in the dropdown list.
     */
    public function test_create_form_shows_valid_coordinators()
    {
        $validTeacher = Teacher::factory()->create();
        $invalidTeacher = Teacher::factory()->create();
        Department::factory()->create();
        Career::factory()->create(['coordinator_id' => $invalidTeacher->id]);

        $response = $this->get(route('careers.create'));

        $response->assertStatus(200);
        $response->assertViewHas('coordinators', function ($coordinators) use ($validTeacher, $invalidTeacher) {
            return $coordinators->contains($validTeacher) && !$coordinators->contains($invalidTeacher);
        });
    }

    /**
     @test
     @lautarvg02
     * Test that the career creation form requires a valid coordinator
     * to be selected before submission.
     */
    public function test_store_requires_a_valid_coordinator()
    {
        Teacher::factory()->create();
        $department = Department::factory()->create();

        $response = $this->post(route('careers.store'), [
            'name' => 'Ingeniería de Software',
            'coordinator_id' => null,
            'department_id' => $department->id,
        ]);

        $response->assertSessionHasErrors(['coordinator_id' => 'The coordinator id field is required.']);
    }


    /**
      @test
      @lautarvg02
     * Test that the system prevents creating duplicate careers
     * in the same department.
     */
    public function test_store_does_not_allow_duplicate_careers_in_same_department()
    {
        $teacher = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();

        $department = Department::factory()->create();

        Career::factory()->create([
            'name' => 'Ingeniería de Software',
            'department_id' => $department->id,
            'coordinator_id' => $teacher2->id,
        ]);

        $response = $this->post(route('careers.store'), [
            'name' => 'Ingeniería de Software',
            'coordinator_id' => $teacher->id,
            'department_id' => $department->id,
        ]);

        $response->assertSessionHasErrors(['name' => 'Ya existe una carrera con el mismo nombre.']);
    }

    /**
      @test
      @lautarvg02
     * Test that a career is created successfully when valid data
     * is submitted through the form.
     */
    public function test_store_creates_career_with_valid_data()
    {
        $teacher = Teacher::factory()->create();

        $department = Department::factory()->create();

        $response = $this->post(route('careers.store'), [
            'name' => 'Ingeniería de Software',
            'coordinator_id' => $teacher->id,
            'department_id' => $department->id,
        ]);

        $this->assertDatabaseHas('careers', [
            'name' => 'Ingeniería de Software',
            'coordinator_id' => $teacher->id,
            'department_id' => $department->id,
        ]);

        $response->assertRedirect(route('careers.index'));
        $response->assertSessionHas('success', 'Carrera creada exitosamente.');
    }
}
