<?php

namespace Tests\Feature\Teacher;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherEmptyTest extends TestCase
{
    use RefreshDatabase;
   
    /**
     * Test que verifica que se muestra un mensaje cuando no hay profesores.
     *
     * @return void
     *  
     * @test
       @juan2645
     */
    
    public function test_index_shows_empty_message_when_no_teachers()
    {
       
        // Hacemos la solicitud al método index del controlador
        $response = $this->get(route('teachers.index'));

        // Verificamos que el estado de la respuesta sea 200
        $response->assertStatus(200);

        // Verificamos que se muestre el mensaje de tabla vacía
        $response->assertSee('!La tabla de docentes, esta vacia!', false);
    }
  
}
