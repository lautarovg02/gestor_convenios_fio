<?php

namespace Tests\Feature\Teacher;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherExceptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica que se muestra un mensaje de error
     * cuando ocurre una excepción al obtener la lista de profesores.
     *
     * @return void
     * 
     * @test
       @juan2645
     */
         
     public function test_index_shows_error_message_on_exception()
     {
        
        // Hacemos mock del modelo Teacher y lanzamos una excepción cuando se llame a getAllWithRoles
        $mockTeacher = \Mockery::mock('alias:' . Teacher::class);
        $mockTeacher->shouldReceive('getAllWithRoles')
            ->once()
            ->andThrow(new \Exception('Error de prueba'));

        // Hacemos la solicitud
        $response = $this->get(route('teachers.index'));

        // Verificamos que el mensaje de error está presente
        $expectedMessage = 'No se pudo recuperar la información de empresas en este momento. Por favor, inténtelo más tarde.';
        $response->assertStatus(200);
        $response->assertViewHas('errorMessage', $expectedMessage);
        $response->assertSeeText($expectedMessage);
    }
}   