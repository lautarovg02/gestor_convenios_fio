<?php

namespace Tests\Feature\Teacher;

use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * @dairagalceran
    */
    public function it_loads_the_edit_page_with_teacher_data()
    {
        // Crear un docente de prueba
        $teacher = Teacher::factory()->create();

        // Realizar la solicitud al método edit
        $response = $this->get(route('teachers.edit', $teacher->id));

        // Verificar que la vista cargue correctamente
        $response->assertStatus(200);
        $response->assertViewIs('teachers.edit');
        $response->assertViewHas('teacher', function ($viewTeacher) use ($teacher) {
            return $viewTeacher->id === $teacher->id;
        });
    }


    /** @test
     *
     * that ensure that a teacher's edit page displays correctly with the teacher's data loaded
     * @dairagalceran
     */
    public function it_displays_the_edit_form_correctly()
    {
        // Arrange: Crea un DOCENTE
        $teacher = Teacher::factory()->create();

        // Act: Visita la página de edición
        $response = $this->get(route('teachers.edit', $teacher));

        // Assert: Verifica que la respuesta sea exitosa y contenga los datos del docente
        $response->assertStatus(200)
                ->assertSee($teacher->name)
                ->assertSee($teacher->lastname)
                ->assertSee($teacher->dni);
    }


    /** @test
     *
     * that verify that the system allows a teacher's data to be updated correctly.
     * @dairagalceran
    */
    public function it_updates_teacher_successfully()
    {
        // Arrange: Crea un docente
        $teacher = Teacher::factory()->create();

        $data = [
            'name' => 'Nombre',
            'lastname' => 'Apellido',
            'dni' => '34567890',
        ];

        // Act: Envía el formulario de actualización
        $response = $this->patch(route('teachers.update', $teacher), $data);

        // Assert: Verifica que se redirige correctamente y que se actualizan los datos
        $response->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('teachers', $data);
    }

    /** @test
      * that verifies  the system correctly handles validation errors
      *when attempting to update a teacher with invalid data
      *@dairagalceran
      */
    public function it_shows_validation_errors_on_invalid_data()
    {
        // Arrange: Crea una compañía
        $teacher = Teacher::factory()->create();

        // Datos inválidos (sin name y dni con string)
        $data = [
            'dni' => 'nombre',
            'name' => '',
            'lastname' => 'Apellido',
        ];

        // Act: Intenta actualizar el docente con datos inválidos
        $response = $this->patch(route('teachers.update', $teacher->id), $data);

        // Assert: Verifica que la validación se produzca y redirija de nuevo al formulario
        $response->assertRedirect()
                ->assertSessionHasErrors([ 'dni', 'name']);
    }


    /** @test
      *  that verifies  the system correctly handles validation success messages
      * when attempting to update a teacher with valid data
      *@dairagalceran
      */
    public function it_shows_success_flash_message_on_successful_update()
    {
        // Arrange: Crea un docente y sus datos
        $teacher = Teacher::factory()->create();

        $data = [
            'name' => 'Nombre',
            'lastname' => 'Apellido',
            'dni' => '34567890',
            'cuil' => '12345678901',
        ];

        // Act: Envía el formulario de actualización
        $response = $this->patch(route('teachers.update', $teacher), $data);

        // Assert: Verifica que se redirige con un mensaje de éxito
        $response->assertRedirect(route('teachers.index'));
        $response->assertSessionHas('success', 'Docente actualizado exitosamente.');
    }

    /** @test
     * that validate that required fields are asked when updating a teacher,
     * @dairagalceran
     */
    public function it_requires_required_fields_when_update()
    {
        // Arrange: Crea un docente
        $teacher = Teacher::factory()->create();

        // Act: envía el formulario sin ningún dato
        $response = $this->patch(route('teachers.update' , $teacher), []);

        // Assert: verifica que se redirige a la misma página y muestra los errores
        $response->assertSessionHasErrors(['name', 'lastname', 'dni']);
    }

    /** @test
    * that ensure  the system correctly validates mandatory fields when updating a teacher,
    * rejecting requests with incomplete or invalid data.
    * @dairagalceran
    */
    public function it_loads_existing_company_data_in_the_edit_form()
    {
         // Arrange: Crea un docente
        $teacher = Teacher::factory()->create([
            'name' => 'Nombre',
            'lastname' => 'Apellido',
            'dni' => '34567890',
            'cuil' => '12345678901',
            'is_dean' => true,
            'is_rector' =>false,
        ]);

        // Acceder al formulario de edición
        $response = $this->get(route('teachers.edit', $teacher->id));

        // Comprobar que el formulario carga los datos actuales
        $response->assertStatus(200);
        $response->assertSee($teacher->name);
        $response->assertSee($teacher->lastname);
        $response->assertSee($teacher->cuil);
        $response->assertSee($teacher->dni);
        $response->assertSee($teacher->is_rector);
        $response->assertSee($teacher->is_dean);

    }



}
