<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;

class CareerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testea que el método index devuelva la vista con las carreras.
     *
      @test
      @lautarov02
     */
    public function testIndexReturnsViewWithCareers()
    {
        Teacher::factory()->count(10)->create();
        Department::factory()->count(2)->create();

        // Crea algunos datos de prueba
        Career::factory()->count(5)->create();


        // Realiza la solicitud al método index
        $response = $this->get(route('careers.index'));

        // Verifica que el estado de la respuesta sea correcto
        $response->assertStatus(200);
        $response->assertViewIs('careers.index');
        $response->assertViewHas('careers');
    }

    /**
     * Testea que el método index devuelva un mensaje para cuando no hay ningun resultado.
     *
      @test
      @lautarov02
     */
    public function testIndexReturnsNoResultsMessage()
    {

        Teacher::factory()->count(10)->create();
        Department::factory()->create();
        Career::factory()->count(5)->create();

        // Realiza la solicitud al método index
        $response = $this->get(route('careers.index', ['search' => 'Nonexistent']));

        // Verifica que el estado de la respuesta sea correcto
        $response->assertStatus(200);
        $response->assertViewIs('careers.index');
        $response->assertSeeText('No se encontraron resultados para la búsqueda: ');
    }

}
