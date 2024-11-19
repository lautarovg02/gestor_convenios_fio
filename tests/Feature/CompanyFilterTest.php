<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Company;
use App\Models\City;
use App\Models\Province;


use Tests\TestCase;

class CompanyFilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the search form filters companies based on the selected city, sector, and scope.
     * It verifies that only the companies matching the filters appear in the results.
      @test
      @lautarovg02
     */
    public function test_the_form_filters_companies_correctly()
    {
        Province::factory()->create();

        Province::factory()->create();

        // Arrange
        $city1 = City::factory()->create();
        $city2 = City::factory()->create();

        $company = Company::factory()->create();
        $company2 = Company::factory()->create();

        // Act
        $response = $this->get(route('companies.index', [
            'city' => $city1->id,
            'scope' => $company->scope,
            'sector' => $company->sector,
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($company->sector);
    }

    /**
     * Test that submitting the form with no filters displays all enabled companies.

      @test
      @lautarovg02
     */
    public function test_the_form_displays_all_companies_when_no_filters_are_applied()
    {
        Province::factory()->create();

        // Arrange
        $city = City::factory()->create(['name' => 'City One']);

        $company = Company::factory()->create();
        $company2 = Company::factory()->create();

        // Act
        $response = $this->get(route('companies.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($company->company_name);
        $response->assertSee($company2->company_name);
    }

    /**
     * Test that the form with invalid filters displays an error message.
     *
      @test
      @lautarovg02
     */
    public function test_the_form_with_invalid_filters_shows_error_message()
    {
        Province::factory()->create();

        // Arrange
        $city = City::factory()->create(['name' => 'City One']);
        $company = Company::factory()->create();
        $company2 = Company::factory()->create();
        //     // Act
        $response = $this->get(route('companies.index', [
            'city' => 999, // Invalid city ID
            'sector' => 'Nonexistent Sector',
            'scope' => 'Nonexistent Scope',
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('No se ha encontrado ninguna compañía con los filtros seleccionados.');
    }


    /**
     @test
     @lautarovg02
     * Test to verify the "Clear" button resets filters and displays all companies.
     */
    public function the_clear_button_resets_filters_and_displays_all_companies()
    {
        Province::factory()->create();

        // build cities
        $city1 = City::factory()->create();
        $city2 = City::factory()->create();


        // build companies
        $company1 = Company::factory()->create();

        $company2 = Company::factory()->create();

        // Simulate form submission with filters applied
        $response = $this->get(route('companies.index', ['sector' => $company1->sector]));
        $response->assertStatus(200);
        $response->assertSee($company1->company_name);
        $response->assertDontSee($company2->company_name);

        // Simulate click on clear button
        $response = $this->get(route('companies.index'));
        $response->assertStatus(200);

        // Test that now all the company are visible
        $response->assertSee($company1->company_name);
        $response->assertSee($company2->company_name);
    }
}
