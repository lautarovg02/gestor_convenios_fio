<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\CompanyEntity;
use App\Models\Company;
use App\Models\Province;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyEditFormTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        \Database\Factories\CompanyEntityFactory::resetEntities();
    }
    /** @test */
    public function it_displays_the_edit_form_correctly()
    {
        // Arrange: Crea una compañía y algunas ciudades para la prueba
        Province::factory()->create();
        $city = City::factory()->create();
        $companyEntity = CompanyEntity::factory()->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        // Act: Visita la página de edición
        $response = $this->get(route('companies.edit', $company));

        // Assert: Verifica que la respuesta sea exitosa y contenga los datos de la compañía
        $response->assertStatus(200)
            ->assertSee($companyEntity->name)
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
        $companyEntity = CompanyEntity::factory()->create();
        $companyEntity2 = CompanyEntity::factory()->create();

        $company = Company::factory()->create();
        $data = [
            'denomination' => 'Nueva Razón social',
            'cuit' => '12345678901',
            'company_name' => 'Nuevo Nombre de la Empresa',
            'sector' => 'Nuevo Sector',
            'entity_id' => $companyEntity2->id,
            'company_category' => 'Nueva Categoría',
            'scope' => 'Nuevo Ámbito',
            'street' => 'Nueva Calle',
            'number' => '123',
            'city_id' => $city->id,
        ];

        // Act: Envía el formulario de actualización
        $response = $this->patch(route('companies.update', $company), $data);

        // Assert: Verifica que se redirige correctamente y que se actualizan los datos
        $this->assertDatabaseHas('companies', $data);
        $response->assertRedirect(route('companies.index'));
    }

    /** @test */
    public function it_shows_validation_errors_on_invalid_data()
    {
        // Arrange: Crea una compañía
        Province::factory()->create();
        $city = City::factory()->create();
        $companyEntity = CompanyEntity::factory()->create();
        $company = Company::factory()->create();

        $data = [
            'denomination' => 'Nueva Denominación',
            'cuit' => '12345678901',
            'company_name' => 'Nuevo Nombre de la Empresa',
            'sector' => 'Nuevo Sector',
            'entity_id' => $companyEntity->id,
            'company_category' => 'Nueva Categoría',
            'scope' => 'nacional',
            'street' => 'Nueva Calle',
            'number' => '123',
            'city_id' => $city->id,
        ];


        // Act: Intenta actualizar la compañía con datos inválidos
        $response = $this->patch(route('companies.update', $company->id), $data);

        // Assert: Verifica que la validación se produzca y redirija de nuevo al formulario
        $response->assertSessionHasErrors([ 'entity_id']);
    }

    /** @test */
    public function it_shows_success_flash_message_on_successful_update()
    {
        // Arrange: Crea una compañía y una ciudad
        Province::factory()->create();
        $city = City::factory()->create();
        $companyEntity = CompanyEntity::factory()->create(['name' => 'eDDSDSD']);
        $company = Company::factory()->create();


        $data = [
            'denomination' => 'd Denominación',
            'cuit' => '12345678901',
            'company_name' => 'ds Nombre de la Empresa',
            'sector' => 'd Sector',
            'entity_id' => $companyEntity->id,
            'company_category' => 'dsa Categoría',
            'scope' => 'NACIONAL',
            'street' => 'sdsds Calle',
            'number' => '1213',
            'city_id' => $city->id,
        ];


        dd($data, $companyEntity);
        // Act: Envía el formulario de actualización
        $response = $this->patch(route('companies.update', $company), $data);

        // Assert: Verifica que se redirige con un mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa actualizada exitosamente.');
    }
    public function it_requires_required_fields_when_update()
    {
        // Arrange: Crea una compañía
        Province::factory()->create();
        $city = City::factory()->create();
        $companyEntity = CompanyEntity::factory()->create();
        $company = Company::factory()->create();

        // Act: envía el formulario sin ningún dato
        $response = $this->patch(route('companies.update', $company), []);

        // Assert: verifica que se redirige a la misma página y muestra los errores
        $response->assertSessionHasErrors(['denomination', 'cuit', 'city_id']);
    }

    public function test_update_company_with_existing_entity(): void
    {
        Province::factory()->create();

        City::factory()->count(4)->create();

        // Arrange: Crear una empresa y una entidad existente
        $companyEntity = CompanyEntity::factory()->create(['name' => 'Privada con fines de lucro']);
        $company = Company::factory()->create(['entity_id' => $companyEntity->id]);

        // Datos de actualización validando la entidad existente
        $data = [
            'denomination' => 'Updated Company',
            'cuit' => '12345678901',
            'company_name' => 'Updated Name',
            'sector' => 'Sector Updated',
            'company_category' => 'Category Updated',
            'scope' => 'nacional', // Radio button selection
            'entity_id' => $companyEntity->id, // Existing entity
            'street' => 'New Street',
            'number' => '123',
            'city_id' => City::factory()->create()->id,
        ];

        // Act: Realizar la petición de actualización
        $response = $this->put(route('companies.update', $company->id), $data);

        // Assert: Verificamos redirección y mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa actualizada exitosamente.');

        // Verificamos que la entidad de la empresa haya permanecido como se esperaba
        $this->assertDatabaseHas('companies', [
            'denomination' => 'Updated Company',
            'entity_id' => $companyEntity->id,
        ]);
    }

    public function test_update_company_with_new_entity(): void
    {
        Province::factory()->create();

        City::factory()->count(4)->create();
        $companyEntity = CompanyEntity::factory()->create(['name' => 'Privada con fines de lucro']);
        $company = Company::factory()->create(['entity_id' => $companyEntity->id]);

        // Datos de actualización validando la entidad existente
        $data = [
            'denomination' => 'Updated Company',
            'cuit' => '12345678901',
            'company_name' => 'Updated Name',
            'sector' => 'Sector Updated',
            'company_category' => 'Category Updated',
            'scope' => 'nacional', // Radio button selection
            'entity_id' => $companyEntity->id, // Existing entity
            'street' => 'New Street',
            'number' => '123',
            'city_id' => City::factory()->create()->id,
        ];


        // Act: Realizar la petición de actualización
        $response = $this->put(route('companies.update', $company->id), $data);

        // Assert: Verificamos redirección y mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa actualizada exitosamente.');

        // Verificamos que la nueva entidad se creó y se relacionó a la empresa
        $this->assertDatabaseHas('company_entities', [
            'name' => 'Nueva entidad no registrada',
        ]);

        $newEntity = CompanyEntity::where('name', 'Nueva entidad no registrada')->first();
        $this->assertDatabaseHas('companies', [
            'denomination' => 'New Company',
            'entity_id' => $newEntity->id,
        ]);
    }

    /** @test */
    public function it_loads_existing_company_data_in_the_edit_form()
    {
        // Crear ciudad y empresa de prueba
        Province::factory()->create();
        $companyEntity = CompanyEntity::factory()->create();
        $city = City::factory()->create();
        $company = Company::factory()->create([
            'denomination' => 'Empresa Test',
            'cuit' => '12345678901',
            'city_id' => $city->id,
            'company_name' => 'Nombre Test',
            'sector' => 'Sector Test',
            'company_category' => 'Categoría Test',
            'scope' => 'nacional',
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
        $response->assertSee($companyEntity->name);
        $response->assertSee($company->company_category);
        $response->assertSee($company->street);
        $response->assertSee($company->number);
    }

    /** @test */
    public function it_updates_company_successfully_and_redirects_with_success_message()
    {
        // Crear ciudad y empresa de prueba
        Province::factory()->create();
        $city = City::factory()->create();
        CompanyEntity::factory()->count(5)->create();
        $company = Company::factory()->create(['city_id' => $city->id]);

        // Datos para actualizar la empresa
        $data = [
            'denomination' => 'Empresa Modificada',
            'cuit' => '98765432109',
            'company_name' => 'Nombre Modificado',
            'sector' => 'Sector Modificado',
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
        CompanyEntity::factory()->count(5)->create();

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


}
