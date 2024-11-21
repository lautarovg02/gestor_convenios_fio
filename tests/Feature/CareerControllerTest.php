<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Tests\TestCase;

class CareerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para verificar que las carreras se filtran correctamente por departamento.
     * @lautarovg02
     */

    /**
     * Test para verificar que las carreras se filtran correctamente por departamento.
     * @lautarovg02
     */
    public function test_careers_are_filtered_by_department()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();

        $department1 = Department::factory()->create(['director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['director_id' => $teacher2->id]);

        $career1 = Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Career 1',
            'coordinator_id' => $teacher4->id,
        ]);
        $career2 = Career::factory()->create([
            'department_id' => $department2->id,
            'name' => 'Career 2',
            'coordinator_id' => $teacher3->id,
        ]);


        $response = $this->get(route('careers.index', ['department' => $department1->id]));

        // Asegúrate de buscar el nombre de la carrera, no del departamento
        $response->assertSee($career1->name);
        $response->assertDontSee($career2->name);
    }

    /**
     * Test para verificar que las carreras se ordenan alfabéticamente por defecto.
     * @lautarovg02
     */
    public function test_careers_are_sorted_alphabetically_by_default()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();
        $teacher5 = Teacher::factory()->create();
        $teacher6 = Teacher::factory()->create();

        $department1 = Department::factory()->create(['name' => 'Departamento-1', 'director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['name' => 'Departamento-2', 'director_id' => $teacher2->id]);
        $department3 = Department::factory()->create(['name' => 'Departamento-3', 'director_id' => $teacher6->id]);

        // Crear carreras con diferentes nombres
        Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Zoology',
            'coordinator_id' => $teacher3->id,
        ]);
        Career::factory()->create([
            'name' => 'Biology',
            'department_id' => $department3->id,
            'coordinator_id' => $teacher4->id
        ]);
        Career::factory()->create([
            'name' => 'Anthropology',
            'department_id' => $department2->id,
            'coordinator_id' => $teacher5->id
        ]);

        // Realizar la solicitud
        $response = $this->get(route('careers.index'));

        // Verificar que las carreras estén ordenadas alfabéticamente
        $response->assertSeeInOrder(['Anthropology', 'Biology', 'Zoology']);
    }

    /**
     * Test para verificar que las carreras se ordenan correctamente en orden descendente.
     * @lautarovg02
     */
    public function test_careers_can_be_sorted_in_descending_order()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();
        $teacher5 = Teacher::factory()->create();
        $teacher6 = Teacher::factory()->create();

        $department1 = Department::factory()->create(['name' => 'Departamento-4', 'director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['name' => 'Departamento-5', 'director_id' => $teacher2->id]);
        $department3 = Department::factory()->create(['name' => 'Departamento-6', 'director_id' => $teacher6->id]);

        Teacher::factory()->count(10)->create();


        // Crear carreras con diferentes nombres
        Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Zoology',
            'coordinator_id' => $teacher3->id,
        ]);
        Career::factory()->create([
            'name' => 'Biology',
            'department_id' => $department3->id,
            'coordinator_id' => $teacher4->id
        ]);
        Career::factory()->create([
            'name' => 'Anthropology',
            'department_id' => $department2->id,
            'coordinator_id' => $teacher5->id
        ]);

        // Realizar la solicitud con orden descendente
        $response = $this->get(route('careers.index', ['sort' => 'name', 'direction' => 'desc']));

        // Verificar que las carreras estén ordenadas en orden descendente
        $response->assertSeeInOrder(['Zoology', 'Biology', 'Anthropology']);
    }

    /**
     * Test para verificar que se puede buscar una carrera por su nombre.
     * @lautarovg02
     */
    public function test_careers_can_be_searched_by_name()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();
        $teacher6 = Teacher::factory()->create();

        $department1 = Department::factory()->create(['name' => 'Departamento-16', 'director_id' => $teacher1->id]);
        $department3 = Department::factory()->create(['name' => 'Departamento-121', 'director_id' => $teacher6->id]);
        Teacher::factory()->count(10)->create();

        // Crear carreras con diferentes nombres
        Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Engineering',
            'coordinator_id' => $teacher3->id,
        ]);
        Career::factory()->create([
            'name' => 'Medicine',
            'department_id' => $department3->id,
            'coordinator_id' => $teacher4->id
        ]);

        // Realizar la búsqueda de 'Engineering'
        $response = $this->get(route('careers.index', ['search' => 'Engineering']));

        // Verificar que se encuentre la carrera buscada
        $response->assertSee('Engineering');
        $response->assertDontSee('Medicine');
    }

    /**
     * Test para verificar que buscar y ordenar simultáneamente funciona correctamente.
     * @lautarovg02
     */
    public function test_careers_can_be_searched_and_sorted()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();
        $teacher5 = Teacher::factory()->create();
        $teacher6 = Teacher::factory()->create();

        $department1 = Department::factory()->create(['name' => 'Departamento-8', 'director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['name' => 'Departamento-9', 'director_id' => $teacher2->id]);
        $department3 = Department::factory()->create(['name' => 'Departamento-11', 'director_id' => $teacher6->id]);
        Teacher::factory()->count(10)->create();

        // Crear carreras con diferentes nombres
        Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Zoology',
            'coordinator_id' => $teacher3->id,
        ]);
        Career::factory()->create([
            'name' => 'Engineering',
            'department_id' => $department3->id,
            'coordinator_id' => $teacher4->id
        ]);
        Career::factory()->create([
            'name' => 'Anthropology',
            'department_id' => $department2->id,
            'coordinator_id' => $teacher5->id
        ]);

        // Realizar la búsqueda y ordenar por nombre en orden ascendente
        $response = $this->get(route('careers.index', ['search' => 'Engineering', 'sort' => 'name', 'direction' => 'desc']));

        // Verificar que se muestre solo la carrera buscada
        $response->assertSee('Engineering');
        $response->assertDontSee('Anthropology');
        $response->assertDontSee('Zoology');
    }

    /**
     * Test para verificar que se muestra un mensaje cuando no hay resultados.
     * @lautarovg02
     */
    public function test_no_results_message_is_displayed_when_no_careers_match()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();
        $teacher6 = Teacher::factory()->create();

        $department1 = Department::factory()->create(['name' => 'Departamento-13', 'director_id' => $teacher1->id]);
        $department3 = Department::factory()->create(['name' => 'Departamento-12', 'director_id' => $teacher6->id]);
        Teacher::factory()->count(10)->create();

        // Crear carreras con diferentes nombres
        Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Engineering',
            'coordinator_id' => $teacher3->id,
        ]);
        Career::factory()->create([
            'name' => 'Medicine',
            'department_id' => $department3->id,
            'coordinator_id' => $teacher4->id
        ]);

        // Realizar la búsqueda de una carrera inexistente
        $response = $this->get(route('careers.index', ['search' => 'Physics']));

        // Verificar que se muestra el mensaje de "no hay resultados"
        $response->assertSee('No se encontraron resultados para la búsqueda: ');
    }

    /**
     * Test para verificar que las carreras se filtran por departamento y se ordenan correctamente.
     * @lautarovg02
     */
    public function test_careers_can_be_filtered_by_department_and_sorted()
    {
        $teacher1 = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();
        $teacher3 = Teacher::factory()->create();
        $teacher4 = Teacher::factory()->create();
        $teacher5 = Teacher::factory()->create();
        $teacher6 = Teacher::factory()->create();
        Teacher::factory()->count(10)->create();

        $department1 = Department::factory()->create(['name' => 'Departamento-1', 'director_id' => $teacher1->id]);
        $department2 = Department::factory()->create(['name' => 'Departamento-2', 'director_id' => $teacher2->id]);
        $department3 = Department::factory()->create(['name' => 'Departamento-2', 'director_id' => $teacher6->id]);
        Teacher::factory()->count(10)->create();


        // Crear carreras en diferentes departamentos
        Career::factory()->create([
            'department_id' => $department1->id,
            'name' => 'Anthropology',
            'coordinator_id' => $teacher3->id,
        ]);
        Career::factory()->create([
            'department_id' => $department3->id,
            'name' => 'Zoology',
            'coordinator_id' => $teacher4->id,
        ]);
        Career::factory()->create([
            'department_id' => $department2->id,
            'name' => 'Biology',
            'coordinator_id' => $teacher5->id,
        ]);

        // Filtrar por departamento 1 y ordenar en orden descendente
        $response = $this->get(route('careers.index', [
            'department' => $department1->id,
            'sort' => 'name',
            'direction' => 'desc'
        ]));

        // Verificar que se muestren las carreras del departamento 1 en orden descendente
        $response->assertSeeInOrder(['Zoology', 'Anthropology']);
        $response->assertDontSee('Biology');
    }
}
