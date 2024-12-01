<?php

namespace Tests\Feature;  

use Tests\TestCase;  
use Illuminate\Foundation\Testing\RefreshDatabase;  

class ClientLimitInputControlTest extends TestCase  
{  
    use RefreshDatabase;  

    /** @test */  
    public function it_rejects_inputs_that_exceed_max_length()  
    {  
        $data = [  
            'denomination' => str_repeat('A', 41), // Excede el límite de 40 caracteres  
            'cuit' => str_repeat('1', 12), // Excede el límite de 11 caracteres  
            'company_name' => str_repeat('A', 101), // Excede el límite de 100 caracteres  
            'sector' => str_repeat('A', 41), // Excede el límite de 40 caracteres  
            'company_category' => str_repeat('A', 21), // Excede el límite de 20 caracteres  
            // Asegurarse de que 'ciudad' también esté presente si es obligatorio  
            'ciudad' => 'Ciudad Válida', // Añadir un valor válido aquí  
        ];  

        $response = $this->post(route('companies.store'), $data);  

        // Verificar que las validaciones fallan  
        $response->assertSessionHasErrors([  
            'denomination',  
            'cuit',  
            'company_name',  
            'sector',  
            'company_category',  
        ]);  
    }  

  /** @test */
public function testItAcceptsValidInputs()
{
    // Crear una provincia válida
    $province = \App\Models\Province::factory()->create();

    // Crear una ciudad válida con la provincia asociada
    $city = \App\Models\City::factory()->create(['province_id' => $province->id]);

    $data = [
        'denomination' => str_repeat('A', 40),
        'cuit' => '11111111111',
        'company_name' => 'Empresa de Ejemplo',
        'sector' => 'Tecnología',
        'company_category' => 'Software',
        'street' => 'Calle Ficticia',
        'number' => '123',
        'city_id' => $city->id, // Usar city_id en lugar de city
    ];

    // Enviar los datos y verificar que no haya errores en la sesión
    $response = $this->post(route('companies.store'), $data);

    // Verificar que no haya errores en la sesión
    $response->assertSessionHasNoErrors();

    // Verificar que la empresa se haya guardado correctamente
    $this->assertDatabaseHas('companies', [
        'denomination' => str_repeat('A', 40),
        'cuit' => '11111111111',
        'company_name' => 'Empresa de Ejemplo',
        'sector' => 'Tecnología',
        'company_category' => 'Software',
        'street' => 'Calle Ficticia',
        'number' => '123',
        'city_id' => $city->id, // Verificar que se guarda el ID de la ciudad
    ]);
}



      
}