<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;

class CompanyFormTest extends TestCase
{
    use RefreshDatabase,WithFaker;

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

     /** @test */
    public function it_saves_company_data_correctly()
    {
         // Arrange: Crear una ciudad para asignarla a la compañía
        $province = Province::factory()->create();
        $city = City::factory()->create();

       // Datos válidos para la compañía
        $data = [
            'denomination' => 'Empresa Test',
            'cuit' => '12345678901',
            'company_name' => 'Nombre de la Compañía',
            'sector' => 'Sector',
            'entity' => 'Entidad',
            'company_category' => 'Categoría',
            'scope' => 'Ámbito',
            'street' => 'Calle Falsa',
            'number' => '123',
            'city_id' => $city->id,
        ];

         // Act: Enviar la solicitud de creación
        $response = $this->post(route('companies.store'), $data);

         // Assert: Verificar redirección y mensaje de éxito
        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success', 'Empresa ingresada exitosamente.');

         // Verificar que los datos se guardaron en la base de datos
        $this->assertDatabaseHas('companies', $data);
    }

     /** @test */
    public function it_throws_exception_for_duplicate_cuit()
    {
         // Arrange: Crear una ciudad y una compañía con un cuit específico
        Province::factory()->create();
        $city = City::factory()->create();
        Company::factory()->create([
            'cuit' => '12345678901',
            'city_id' => $city->id,
        ]);

         // Datos duplicados para la compañía
        $data = [
            'denomination' => 'Empresa Duplicada',
            'cuit' => '12345678901',  // cuit duplicado
            'company_name' => 'Otra Compañía',
            'sector' => 'Otro Sector',
            'entity' => 'Otra Entidad',
            'company_category' => 'Otra Categoría',
            'scope' => 'Otro Ámbito',
            'street' => 'Otra Calle',
            'number' => '456',
            'city_id' => $city->id,
        ];

         // Act: Enviar la solicitud de creación con datos duplicados
        $response = $this->post(route('companies.store'), $data);

         // Assert: Verificar redirección con mensaje de error
        $response->assertRedirect();
        $response->assertSessionHasErrors(['error' => 'Cuit duplicado']);

         // Verificar que los datos no se guardaron nuevamente en la base de datos
        $this->assertDatabaseCount('companies', 1);
    }

}
