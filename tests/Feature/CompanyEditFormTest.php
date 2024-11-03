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

      /** @test */
    public function it_requires_required_fields_when_update()
    {
        // Arrange: Crea una compañía
        Province::factory()->create();
        City::factory()->create();
        $company = Company::factory()->create();

        // Act: envía el formulario sin ningún dato
        $response = $this->patch(route('companies.update' , $company), []);

          // Assert: verifica que se redirige a la misma página y muestra los errores
        $response->assertSessionHasErrors(['denomination', 'cuit', 'city_id']);
    }

     /** @test */
    public function it_loads_existing_company_data_in_the_edit_form()
    {
         // Crear ciudad y empresa de prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create([
            'denomination' => 'Empresa Test',
            'cuit' => '12345678901',
            'city_id' => $city->id,
            'company_name' => 'Nombre Test',
            'sector' => 'Sector Test',
            'entity' => 'Entidad Test',
            'company_category' => 'Categoría Test',
            'scope' => 'Ámbito Test',
            'street' => 'Calle Falsa',
            'number' => '123',
        ]);

        // Acceder al formulario de edición
        $response = $this->get(route('companies.edit', $company->id));

        // Comprobar que el formulario carga los datos actuales
        $response->assertStatus(200);
        $response->assertSee($company->denomination);
        $response->assertSee($company->cuit);
        $response->assertSee($company->company_name);
        $response->assertSee($company->sector);
        $response->assertSee($company->entity);
        $response->assertSee($company->company_category);
        $response->assertSee($company->scope);
        $response->assertSee($company->street);
        $response->assertSee($company->number);
    }

    /** @test */
    public function it_updates_company_successfully_and_redirects_with_success_message()
    {
        // Crear ciudad y empresa de prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        // Datos para actualizar la empresa
        $data = [
            'denomination' => 'Empresa Modificada',
            'cuit' => '98765432109',
            'company_name' => 'Nombre Modificado',
            'sector' => 'Sector Modificado',
            'entity' => 'Entidad Modificada',
            'company_category' => 'Categoría Modificada',
            'scope' => 'Ámbito Modificado',
            'street' => 'Calle Modificada',
            'number' => '321',
            'city_id' => $city->id,
        ];

        // Enviar el formulario de actualización
        $response = $this->patch(route('companies.update', $company->id), $data);

        // Verificar redirección y mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa actualizada exitosamente.');

        // Confirmar que los datos se actualizaron en la base de datos
        $this->assertDatabaseHas('companies', $data);
    }


    /** @test */
    public function it_shows_validation_error_when_required_fields_are_missing()
    {
        // Crear ciudad y empresa de prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        // Actuar: Enviar solicitud de actualización sin algunos datos obligatorios
        $response = $this->patch(route('companies.update', $company->id), [
            'denomination' => '',
            'cuit' => '',
            'city_id' => '',
        ]);

        // Verificar que se redirige al formulario de edición con errores de validación
        $response->assertSessionHasErrors(['denomination', 'cuit', 'city_id']);
    }

    /** @test */
    public function it_handles_duplicate_cuit_exception_correctly()
    {
        // Crear dos empresas de prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $existingCompany = Company::factory()->create(['cuit' => '12345678901', 'city_id' => $city->id]);
        $companyToUpdate = Company::factory()->create(['cuit' => '98765432109', 'city_id' => $city->id]);

        // Intentar actualizar la segunda empresa con un CUIT duplicado
        $response = $this->patch(route('companies.update', $companyToUpdate->id), [
            'denomination' => 'Empresa Actualizada',
            'cuit' => '12345678901', // CUIT duplicado
            'company_name' => 'Nombre Actualizado',
            'sector' => 'Sector Actualizado',
            'entity' => 'Entidad Actualizada',
            'company_category' => 'Categoría Actualiza',
            'scope' => 'Ámbito Actualizado',
            'street' => 'Calle Actualizada',
            'number' => '321',
            'city_id' => $city->id,
        ]);

        // Verificar que se redirige al formulario de edición con un mensaje de error por CUIT duplicado
        $response->assertRedirect(route('companies.edit', $companyToUpdate->id));
        $response->assertSessionHasErrors(['error' => 'Cuit duplicado']);
    }

}
