<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Province;
use App\Models\City;


class CompaniesListViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify the function to display the list of companies
      @test
      @lautarovg02
     */
    public function it_displays_list_of_empresas()
    {
        Province::factory()->count(10)->create();
        City::factory()->count(10)->create();
        $companies = Company::factory()->count(8)->create();

        $response = $this->get('/companies');
        $response->assertStatus(200);

        foreach($companies as $c){
            $response->assertSeeText($c->id);
            $response->assertSeeText($c->cuit);
            $response->assertSeeText($c->denomination);
            $response->assertSeeText($c->sector);
            $response->assertSeeText($c->entity);
            $response->assertSeeText($c->company_category);
            $response->assertSeeText($c->city->name);
        }
    }
}
