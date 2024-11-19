<?php

namespace Tests\Feature\Teacher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; 
use Illuminate\Support\Facades\Log;
use App\Models\Teacher;

class TeacherConnectionTest extends TestCase
{
    use RefreshDatabase;
   
    /**
     * Test que verifica que se muestra un mensaje de error
     * cuando hay un error de conexión al obtener la lista de docentes.
     *
     * @return void
     * 
     * @test
       @juan2645
     */
    public function test_index_shows_error_message_on_connection_error()
    {
        // Capturamos el log de errores
        Log::shouldReceive('error')->once();

        // Simulamos el comportamiento del método getAllWithRoles lanzando una excepción de conexión
        $this->mock(Teacher::class, function ($mock) {
            $mock->shouldReceive('getAllWithRoles')
                ->andThrow(new \Exception('Error de conexión a la base de datos'));
        });

        // Hacemos la solicitud al método index del controlador
        $response = $this->get(route('teachers.index'));

        // Verificamos que el estado de la respuesta sea 200
        $response->assertStatus(200);
        
        // Verificamos que se muestre el mensaje de error en la vista
        $response->assertViewHas('errorMessage', 'No se pudo cargar la lista de docentes. Por favor, verifique su conexión.');
    }

}