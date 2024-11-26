<?php

namespace Tests\Feature\Career;

use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CareerFilterTest extends TestCase
{
    use RefreshDatabase;


    /** @test
     * @dairagalceran
     * The Secretary can access a filter on the career listing page.
    */
    public function it_filters_careers_by_department()
    {
        //Arrange
        // Crear dos profesores
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();


        // Crear dos departamentos
        $department1 = Department::factory()->create(['name' => 'Ingeniería', 'director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['name' => 'Medicina', 'director_id' => $teacher2->id]);

        // Crear carreras en los departamentos
        $career1 = Career::factory()->create(['name' => 'Ingeniería de Software', 'department_id' => $department1->id]);
        $career2 = Career::factory()->create(['name' => 'Medicina General', 'department_id' => $department2->id]);

        //Act:
        // Realizar la solicitud de filtrado por departamento
        $response = $this->get(route('careers.index', ['department' => $department1->id]));

        // Verificar que solo se devuelve la carrera del departamento 'Ingeniería'
        $response->assertOk();
        $response->assertSee($career1->name);
        $response->assertDontSee($career2->name);
    }

    /** @test
     * @dairagalceran
     * Verify that a message is displayed when no results are found.
    */
    public function it_shows_no_results_message_when_no_careers_found()
    {
        // Arrange: Ensure no careers exist
        Career::truncate();

        // Act: Visit the careers index page with a search query
        $response = $this->get(route('careers.index', ['search' => 'Nonexistent Career']));

        // Assert: A no results message should be shown
        $response->assertSee('No se encontraron resultados');
    }

    /**
     * Test that submitting the form with no filters displays all enabled companies.
     *   @test
        *  dairagalceran
    */
    public function test_the_form_displays_all_carees_when_no_filters_are_applied()
    {
        //Arrange
        // Crear dos profesores
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();


        // Crear dos departamentos
        $department1 = Department::factory()->create(['name' => 'Ingeniería', 'director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['name' => 'Medicina', 'director_id' => $teacher2->id]);

        // Crear carreras en los departamentos
        $career1 = Career::factory()->create(['name' => 'Ingeniería de Software', 'department_id' => $department1->id, 'coordinator_id' => $teacher3->id]);
        $career2 = Career::factory()->create(['name' => 'Medicina General', 'department_id' => $department2->id, 'coordinator_id' => $teacher4->id]);


        // Act
        $response = $this->get(route('careers.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($career1->name);
        $response->assertSee($career2->name);
    }

}


