<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\DB;



class CompaniesListViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify that all companies are displayed correctly.
     * This test ensures that each field for every company in the database
     * is retrieved and displayed on the view.
     *
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

        foreach ($companies as $c) {
            $response->assertSeeText($c->id);
            $response->assertSeeText($c->cuit);
            $response->assertSeeText($c->denomination);
            $response->assertSeeText($c->sector);
            $response->assertSeeText($c->entity);
            $response->assertSeeText($c->company_category);
            $response->assertSeeText($c->city->name);
        }
    }

    /**
     * Test to verify that an error message is shown if there is a database connection failure.
     * This test simulates a database error and checks if a clear error message is presented to the user.
     *
      @test
      @lautarovg02
     */
    public function test_it_shows_error_message_on_database_failure()
    {
        // Temporarily disable database connection
        DB::shouldReceive('table')->andThrow(new \Exception('Database connection error'));

        // Send a GET request to the companies listing page
        $response = $this->get('/companies');

        // Assert that an appropriate error message is displayed
        $response->assertStatus(500);
        $response->assertSeeText('No se pudo recuperar la información de empresas en este momento. Intente nuevamente más tarde.');
    }

    /**
     * Test to verify that the company list updates automatically when a company is added or removed.
     * This test ensures that changes in the database are reflected on the view.
     *
      @test
      @lautarovg02
     */
    public function test_it_updates_company_list_when_company_is_added_or_removed()
    {
        Province::factory()->count(10)->create();
        City::factory()->count(10)->create();
        // Create an initial set of companies
        $companies = Company::factory()->count(5)->create();

        // Send a GET request to the companies listing page and verify companies are present
        $response = $this->get('/companies');
        $response->assertStatus(200);
        foreach ($companies as $company) {
            $response->assertSeeText($company->company_name);
        }

        // Add a new company
        $newCompany = Company::factory()->create(['company_name' => 'Empresa Nueva']);

        // Verify the new company appears on the updated list
        $response = $this->get('/companies');
        $response->assertSeeText($newCompany->company_name);

        // Delete an existing company
        $companyToDelete = $companies->first();
        $companyToDelete->delete();

        // Verify the deleted company no longer appears on the list
        $response = $this->get('/companies');
        $response->assertDontSeeText($companyToDelete->company_name);
    }

    /**
     * Test to verify that a loading state is shown while the companies data is being fetched.
     * This test checks for a loading indicator initially, and then verifies that it disappears once the companies are displayed.
     *
     @test
     @lautarovg02
     */
    public function test_it_shows_loading_state()
    {
        // Simulate loading state in the view
        $response = $this->get('/companies');

        // Check that the loading state is initially displayed
        $response->assertSeeText('Cargando empresas...');

        Province::factory()->count(10)->create();
        City::factory()->count(10)->create();
        // Ensure the loading state disappears when companies are shown
        $companies = Company::factory()->count(10)->create();
        $response = $this->get('/companies');

        $response->assertDontSeeText('Cargando empresas...');
        foreach ($companies as $company) {
            $response->assertSeeText($company->company_name);
        }
    }
}
