<?php

namespace Tests\Feature;  

use Tests\TestCase;  
use Illuminate\Foundation\Testing\RefreshDatabase;  

class ServerLimitInputControlTest extends TestCase  
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
            'ciudad' => '',  
        ];  

        // Enviar una petición POST  
        $response = $this->post(route('companies.store'), $data);  

        // Verificar que las validaciones fallan, excluyendo 'ciudad'  
        $response->assertSessionHasErrors([  
            'denomination',  
            'cuit',  
            'company_name',  
            'sector',  
            'company_category',  
        ]);  
    }  

    /** @test */  
    public function it_accepts_valid_inputs()  
    {  
        // Crear una provincia válida
        $province = \App\Models\Province::factory()->create();  // Crea una provincia de prueba
    
        // Crear una ciudad válida con la provincia asociada
        $city = \App\Models\City::factory()->create(['province_id' => $province->id]);

        // Preparar datos válidos, incluyendo un valor válido para 'city_id'
        $data = [  
            'denomination' => str_repeat('A', 40), // 40 caracteres  
            'cuit' => str_repeat('1', 11),         // 11 caracteres  
            'company_name' => str_repeat('A', 100), // 100 caracteres  
            'sector' => str_repeat('A', 40),       // 40 caracteres  
            'company_category' => str_repeat('A', 20), // 20 caracteres  
            'city_id' => $city->id,                // Asignar el ID de la ciudad válida
        ];  

        // Enviar la petición POST  
        $response = $this->post(route('companies.store'), $data);  
    
        // Verificar que no haya errores en la sesión  
        $response->assertSessionHasNoErrors();  

        // Verifica que los datos se hayan guardado en la base de datos  
        $this->assertDatabaseHas('companies', [  
        'denomination' => $data['denomination'],  
        'cuit' => $data['cuit'],  
        'company_name' => $data['company_name'],  
        'sector' => $data['sector'],  
        'company_category' => $data['company_category'],  
        'city_id' => $data['city_id'],  // Verificar que se guarda el ID de la ciudad
    ]);  
}


}