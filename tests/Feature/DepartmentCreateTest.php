<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Department;
use App\Models\Teacher;

class DepartmentCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que se muestre el formulario de creación de departamentos.
     *
     * @test
     * @lautarovg02
     */
    public function it_shows_the_creation_form()
    {
        $response = $this->get(route('departments.create'));
        $response->assertStatus(200);

        $response->assertSeeText('Agregar un nuevo departamento');
    }

    /**
     * Verifica que se puede crear un nuevo departamento correctamente.
     *
     * @test
     * @lautarovg02
     */
    public function it_creates_a_new_department()
    {
        Teacher::factory()->count(5)->create();
        $teacher = Teacher::factory()->create();
        $data = [
            'name' => 'Departamento de Ciencias',
            'director_id' => $teacher->id, // Assume there's at least one teacher created
        ];

        $response = $this->post(route('departments.store'), $data);

        // Verifica que el departamento se haya almacenado en la base de datos
        $this->assertDatabaseHas('departments', [
            'name' => 'Departamento de Ciencias',
            'director_id' => $teacher->id,
        ]);

        $response->assertSessionHas('success', 'Departamento creado exitosamente');

    }

    /**
     * Verifica que el campo 'name' es obligatorio para la creación de un departamento.
     *
     * @test
     * @lautarovg02
     */
    public function it_requires_a_name()
    {
        Teacher::factory()->count(5)->create();
        $teacher = Teacher::factory()->create();

        $data = [
            'name' => '',
            'director_id' => $teacher->id,
        ];

        $response = $this->post(route('departments.store'), $data);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Verifica que el campo 'director_id' es obligatorio para la creación de un departamento.
     *
     * @test
     * @lautarovg02
     */
    public function it_requires_a_director_id()
    {
        Teacher::factory()->count(5)->create();
        $teacher = Teacher::factory()->create();

        $data = [
            'name' => 'Nuevo Departamento',
            'director_id' => '',
        ];

        $response = $this->post(route('departments.store'), $data);

        $response->assertSessionHasErrors('director_id');
    }

    /**
     * Verifica que no se permite crear departamentos duplicados con el mismo nombre.
     *
     * @test
     * @lautarovg02
     */
    public function it_does_not_allow_duplicate_departments()
    {
        Teacher::factory()->count(5)->create();
        $teacher = Teacher::factory()->create();
        $teacher2 = Teacher::factory()->create();

        $existingDepartment = Department::create([
            'name' => 'Departamento de Ingeniería',
            'director_id' => $teacher->id,
        ]);


        $data = [
            'name' => 'Departamento de Ingeniería',
            'director_id' => $teacher2->id,
        ];

        $response = $this->post(route('departments.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors(['name' => 'El nombre del departamento ya está en uso.']);
    }

    /**
     * Verifica que se muestre un mensaje de éxito al crear un nuevo departamento.
     *
     * @test
     * @lautarovg02
     */
    public function it_redirects_with_success_message_after_creating_department()
    {
        Teacher::factory()->count(5)->create();
        $teacher = Teacher::factory()->create();

        $data = [
            'name' => 'Departamento de Matemáticas',
            'director_id' => $teacher->id,
        ];

        $response = $this->post(route('departments.store'), $data);
        $response->assertSessionHas('success', 'Departamento creado exitosamente');

        $response->assertRedirect(route('departments.index'));
    }
}
