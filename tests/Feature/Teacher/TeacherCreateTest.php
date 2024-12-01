<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que se muestre el formulario de creación de profesores.
     *
     * @test
     * @rom-L
     */
    public function it_shows_the_teacher_creation_form()
    {
        $response = $this->get(route('teachers.create'));

        $response->assertStatus(200);
        $response->assertSeeText('Agregar nuevo Docente');
    }

    /**
     * Verifica que se puede crear un nuevo profesor correctamente.
     *
     * @test
     * @rom-L
     */
    public function it_creates_a_new_teacher()
    {
        $data = [
            'name' => 'Juan Pérez',
            'lastname' => 'Gómez',
            'dni' => 12345678,
            'cuil' => 20123456780,
            'is_rector' => true,
            'is_dean' => false,
        ];

        $response = $this->post(route('teachers.store'), $data);

        $this->assertDatabaseHas('teachers', [
            'name' => 'Juan Pérez',
            'lastname' => 'Gómez',
            'dni' => 12345678,
        ]);

        $response->assertSessionHas('success', 'Docente ingresado exitosamente.');
        $response->assertRedirect(route('teachers.index'));
    }

    /**
     * Verifica las validaciones de los campos obligatorios.
     *
     * @test
     * @rom-L
     */
    public function it_requires_mandatory_fields_to_create_teacher()
    {
        $data = [
            'name' => '',
            'lastname' => '',
            'dni' => '',
            'cuil' => '',
            'is_rector' => '',
            'is_dean' => '',
        ];

        $response = $this->post(route('teachers.store'), $data);

        $response->assertSessionHasErrors([
            'name', 'lastname', 'dni', 'is_rector', 'is_dean',
        ]);
    }

    /**
     * Verifica que el CUIL debe contener el DNI en la posición correcta.
     *
     * @test
     * @rom-L
     */
    public function it_validates_cuil_contains_dni_in_correct_position()
    {
        $data = [
            'name' => 'Juan Pérez',
            'lastname' => 'Gómez',
            'dni' => 12345678,
            'cuil' => 30123656789, // CUIL incorrecto
            'is_rector' => true,
            'is_dean' => false,
        ];

        $response = $this->post(route('teachers.store'), $data);

        $response->assertSessionHasErrors(['cuil']);
    }

    /**
     * Verifica que el DNI y el CUIL deben tener el formato correcto.
     *
     * @test
     * @rom-L
     */
    public function it_requires_valid_dni_and_cuil_format()
    {
        $data = [
            'name' => 'Juan Pérez',
            'lastname' => 'Gómez',
            'dni' => 'invalid_dni',
            'cuil' => 'invalid_cuil',
            'is_rector' => true,
            'is_dean' => false,
        ];

        $response = $this->post(route('teachers.store'), $data);

        $response->assertSessionHasErrors(['dni', 'cuil']);
    }

    /**
     * Verifica que no se permite crear profesores duplicados con el mismo DNI.
     *
     * @test
     * @rom-L
     */
    public function it_does_not_allow_duplicate_teachers_by_dni()
    {
        Teacher::factory()->create(['dni' => 12345678]);

        $data = [
            'name' => 'Otro Nombre',
            'lastname' => 'Gómez',
            'dni' => 12345678, // DNI duplicado
            'cuil' => 20123456780,
            'is_rector' => true,
            'is_dean' => false,
        ];

        $response = $this->post(route('teachers.store'), $data);

        $response->assertSessionHasErrors();
    }

}

