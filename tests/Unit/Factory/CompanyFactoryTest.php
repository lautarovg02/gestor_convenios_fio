<?php

namespace Tests\Unit;

use App\Models\City;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;


class CompanyFactoryTest extends TestCase
{

    use RefreshDatabase;



    /**
     * Test that the factory creates a Company with valid attributes.
        @dairagalceran
        @test
     */
    public function test_company_factory_creates_valid_company()
    {
        // Crear al menos una provincia antes de crear la compañía
        Province::factory()->create();

        // Crear al menos una ciudad antes de crear la compañía
        City::factory()->create();
        //Crear compania con ciudad y provincia
        $company = Company::factory()->create();


        $this->assertNotNull($company);
        $this->assertDatabaseHas('companies', [
            'denomination' => $company->denomination,
            'cuit' => $company->cuit,
            'company_name' => $company->company_name,
            'sector' => $company->sector,
            'entity' => $company->entity,
            'company_category' => $company->company_category,
            'scope' => $company->scope,
            'street' => $company->street,
            'number' => $company->number,

        ]);
    }

    /**
     * Test that the factory generates unique cuit.
        @dairagalceran
        @test
     */
    public function test_factory_generates_unique_cuit()
    {
        Province::factory()->create();
        City::factory()->create();
        Company::factory()->create(['cuit' => '27234155001']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Company::factory()->create(['cuit' => '27234155001']);
    }

    /**
     * Test that the factory generates unique cuit.
        @dairagalceran
        @test
     */
    public function test_company_factory_generates_valid_cuit_with_eleven_digits()
    {
        Province::factory()->create();
        City::factory()->create();
        $company = Company::factory()->create();
        $cuit = $company->cuit;

        // Verificar que el CUIT tiene exactamente 11 caracteres
        $this->assertEquals(11, strlen($cuit));

        // Verificar que el CUIT es numérico
        $this->assertMatchesRegularExpression('/^\d{11}$/', $cuit, "El CUIT debe tener exactamente 11 dígitos.");
    }

    /**
     * Test that the factory has all necessary attributes defined.
        @dairagalceran
        @test
     */
    public function test_company_factory_has_required_attributes()
    {
        Province::factory()->create();
        City::factory()->create();
        $company = Company::factory()->make();

        $this->assertNotNull($company->denomination);
        $this->assertNotNull($company->cuit);
        $this->assertNotNull($company->city_id);
    }

}
