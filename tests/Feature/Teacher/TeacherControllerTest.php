<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Teacher;
use App\Http\Controllers\TeacherController;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the list of teachers can be accessed.
     *
     * @return void
     *
     * @test
       @juan2645
     */
    public function testListTeachers()
    {
        // Generar datos de prueba
        $teachers = Teacher::factory()->count(9)->create();

        // Hacer una solicitud a la ruta de la lista de docentes
        $response = $this->get('/teachers');
        // Verificar que la respuesta sea correcta
        $response->assertStatus(200);

        // Verificar que se muestren los datos de los docentes
        foreach ($teachers as $teacher) {
            $response->assertSeeText($teacher->name . " " . $teacher->lastname);
            $response->assertSeeText($teacher->dni);
        }
    }
}
