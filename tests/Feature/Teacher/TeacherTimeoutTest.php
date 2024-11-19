<?php

namespace Tests\Feature\Teacher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;

class TeacherTimeoutTest extends TestCase
{
    use RefreshDatabase;
   
    public function test_index_shows_error_message_on_timeout()
    {
        // Capturamos el log de errores
        Log::shouldReceive('error')->once();

        // Simulamos el comportamiento del método getAllWithRoles lanzando una excepción de tiempo de espera
        $this->mock(Teacher::class, function ($mock) {
            $mock->shouldReceive('getAllWithRoles')
                ->andThrow(new \Exception('Tiempo de espera agotado'));
        });

        // Hacemos la solicitud al método index del controlador
        $response = $this->get(route('teachers.index'));

        // Verificamos que el estado de la respuesta sea 200
        $response->assertStatus(200);
        
        // Verificamos que se muestre el mensaje de error en la vista
        $response->assertViewHas('errorMessage', 'La carga de la lista de docentes está tardando demasiado. Por favor, inténtelo más tarde.');
    }

    
}
