<?php

namespace Tests\Feature\Teacher;

use App\Models\Career;
use App\Models\City;
use App\Models\Department;
use App\Models\Province;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherFilterTest extends TestCase
{

    use RefreshDatabase;

    /**
    * @test
    * @dairagalceran
    * Test to verify the "Clear" button resets filters and displays all teachers.
    */
    public function test_clear_button_resets_filters_and_displays_all_teachers()
    {
        // Arrange
        $teachers = Teacher::factory()->count(5)->create();
        $department = Department::factory()->create();

        $department->teacher()->associate($teachers[2]);
        $department->save();

        $career = Career::factory()->create();
        $career1 = Career::factory()->create();

        $career->teachers()->attach($teachers[0]->id);
        $career->teachers()->attach($teachers[3]->id);
        $career->teachers()->attach($teachers[4]->id);
        $career1->teachers()->attach($teachers[1]->id);

        // Act
        // Simulate form submission with filters applied
        $response = $this->get(route('teachers.index', ['career' => $career->name]));
        $response->assertStatus(200);
        $response->assertDontSee($teachers[1]->name);
        $response->assertSee($teachers[0]->id);
        $response->assertSee($teachers[3]->id);
        $response->assertSee($teachers[4]->id);

        // Simulate click on clear button
        $response = $this->get(route('teachers.index'));
        $response->assertStatus(200);

        // Test that now all the teachers are visible
        $response->assertSee($teachers[0]->name);
        $response->assertSee($teachers[3]->name);
        $response->assertSee($teachers[4]->name);
        $response->assertSee($teachers[1]->name);
        $response->assertSee($teachers[2]->name);
    }

    /**
     * Test that the search form filters teachers based on the selected career.
     * It verifies that only the companies matching the filters appear in the results.
     *@test
    *  @dairagalceran
     */

    public function test_the_form_filter_by_career_returns_correct_results()
    {
        // Arrange
        $teachers = Teacher::factory()->count(5)->create();
        $department = Department::factory()->create();
        $department->teacher()->associate($teachers[2]);
        $department->save();

        $career = Career::factory()->create();
        $career1 = Career::factory()->create();

        $career->teachers()->attach($teachers[0]->id);
        $career->teachers()->attach($teachers[3]->id);
        $career->teachers()->attach($teachers[4]->id);
        $career1->teachers()->attach($teachers[1]->id);

        // Act
        $response = $this->get(route('teachers.index', [
            'career' => $career->name,
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($career->name);
        $response->assertSee($teachers[0]->id);
        $response->assertSee($teachers[4]->id);
        $response->assertDontSee($teachers[1]->name);
        $response->assertDontSee($teachers[2]->name);
    }


    /**
     * Test that submitting the form with no filters displays all enabled teachers.
    *@test
    *@dairagalceran
     */

    public function test_the_form_displays_all_teachers_when_no_filters_are_applied()
    {
        // Arrange
        $teachers = Teacher::factory()->count(9)->create();
        $department = Department::factory()->create();

        $department->teacher()->associate($teachers[2]);
        $department->save();

        $career = Career::factory()->create(['department_id' => $department->id]);
        $career1 = Career::factory()->create(['department_id' => $department->id]);

        $career->teachers()->attach($teachers[0]->id);
        $career1->teachers()->attach($teachers[1]->id);

        // Act
        $response = $this->get(route('teachers.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($teachers[0]->name);
        $response->assertSee($teachers[1]->name);
        $response->assertSee($teachers[2]->name);
    }

    public function test_filter_teachers_by_career_and_role()
    {
        // Arrange
        // Crear docentes
        $teachers = Teacher::factory()->count(3)->create();

        // Asignar roles
        $department = Department::factory()->create([
            'director_id' => $teachers[2]->id,
        ]);

        // Crear carreras y relacionarlas con docentes
        $career1 = Career::factory()->create();
        $career2 = Career::factory()->create();
        $career1->teachers()->attach($teachers[0]->id);
        $career2->teachers()->attach($teachers[1]->id);

        $career1->update(['coordinator_id' => $teachers[0]->id]);


        $teacher = Teacher::factory()->create(['id' => 1]);
        $career = Career::factory()->create(['coordinator_id' => $teacher->id]);

    $this->assertEquals(1, $career->coordinator_id);


        dump($teachers[0].'  '. $career1);
        // Filtrar por carrera y rol
        $filters = [
            'career' => $career1->id,
            'role' => Teacher::ROLE_COORDINATOR,
        ];

        // Act
        $response = $this->get(route('teachers.index', $filters));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($teachers[0]->name); // Coordinador de Career1
        $response->assertDontSee($teachers[1]->name); // No pertenece a Career1
        $response->assertDontSee($teachers[2]->name); // Es director, no coordinador
    }


}

