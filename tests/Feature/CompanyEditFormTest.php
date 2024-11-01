<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyEditFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_edit_form_correctly()
    {
        // Arrange: Crea una compañía y algunas ciudades para la prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        // Act: Visita la página de edición
        $response = $this->get(route('companies.edit', $company));

        // Assert: Verifica que la respuesta sea exitosa y contenga los datos de la compañía
        $response->assertStatus(200)
                ->assertSee($company->denomination)
                ->assertSee($company->cuit)
                ->assertSee($city->name);
    }

    /** @test */
    public function it_updates_the_company_successfully()
    {
        // Arrange: Crea una compañía y algunas ciudades para la prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        $data = [
            'denomination' => 'Nueva Denominación',
            'cuit' => '12345678901',
            'company_name' => 'Nuevo Nombre de la Empresa',
            'sector' => 'Nuevo Sector',
            'entity' => 'Nueva Entidad',
            'company_category' => 'Nueva Categoría',
            'scope' => 'Nuevo Ámbito',
            'street' => 'Nueva Calle',
            'number' => '123',
            'city_id' => $city->id,
        ];

        // Act: Envía el formulario de actualización
        $response = $this->patch(route('companies.update', $company), $data);

        // Assert: Verifica que se redirige correctamente y que se actualizan los datos
        $response->assertRedirect(route('companies.index'));
        $this->assertDatabaseHas('companies', $data);
    }

    /** @test */
    public function it_shows_validation_errors_on_invalid_data()
    {
        // Arrange: Crea una compañía
        Province::factory()->create();
        City::factory()->create();
        $company = Company::factory()->create();

        // Datos inválidos (sin cuit)
        $data = [
            'denomination' => '',
            'cuit' => '',
            'company_name' => 'Nombre de la Empresa',
            'sector' => 'Sector',
            'entity' => 'Entidad',
            'company_category' => 'Categoría',
            'scope' => 'Ámbito',
            'street' => 'Calle',
            'number' => '123',
            'city_id' => 'invalido', // Valor inválido para city_id
        ];

        // Act: Intenta actualizar la compañía con datos inválidos
        $response = $this->patch(route('companies.update', $company->id), $data);

        // Assert: Verifica que la validación se produzca y redirija de nuevo al formulario
        $response->assertRedirect()
                ->assertSessionHasErrors(['denomination', 'cuit', 'city_id']);
    }

    /** @test */
    public function it_shows_success_flash_message_on_successful_update()
    {
        // Arrange: Crea una compañía y una ciudad
        Province::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        $data = [
            'denomination' => 'Denominación Actualizada',
            'cuit' => '12345678901',
            'company_name' => 'Nombre de la Empresa',
            'sector' => 'Sector',
            'entity' => 'Entidad',
            'company_category' => 'Categoría',
            'scope' => 'Ámbito',
            'street' => 'Calle',
            'number' => '123',
            'city_id' => $city->id,
        ];

        // Act: Envía el formulario de actualización
        $response = $this->patch(route('companies.update', $company), $data);

        // Assert: Verifica que se redirige con un mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa actualizada exitosamente.');
    }
}
