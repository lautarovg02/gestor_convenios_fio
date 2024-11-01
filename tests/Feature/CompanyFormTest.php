<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_create_company_form()
    {
        // Arrange: crea una ciudad para que esté disponible en el formulario
        Province::factory()->create(['id' =>5]);
        City::factory()->create(['id' => 2, 'name' => 'Test City' ]);

        // Act: solicita la vista del formulario
        $response = $this->get(route('companies.create'));

        // Assert: verifica que se cargue correctamente
        $response->assertStatus(200)
                ->assertViewIs('companies.create')
                ->assertSee('Agregar nueva empresa')
                ->assertSee('Ciudad');
    }

    /** @test */
    public function it_requires_required_fields()
    {
        // Act: envía el formulario sin ningún dato
        $response = $this->post(route('companies.store'), []);

        // Assert: verifica que se redirige a la misma página y muestra los errores
        $response->assertSessionHasErrors(['denomination', 'cuit', 'city_id']);
    }

    /** @test */
    public function it_shows_flash_message_on_successful_submission()
    {
        // Arrange: prepara los datos para el envío exitoso
        Province::factory()->create(['id' =>6]);
        $city = City::factory()->create();
        $data = [
            'denomination' => 'Empresa Test',
            'cuit' => '12345678901',
            'city_id' => $city->id,
        ];

        // Act: envía el formulario
        $response = $this->post(route('companies.store'), $data);

        // Assert: verifica que se redirige con un mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa ingresada exitosamente.');
    }


}
