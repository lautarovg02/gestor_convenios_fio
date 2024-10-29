<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use App\Models\City;
use App\Models\Province;

class CompanySearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that companies can be retrieved based on search criteria like name, CUIT, sector, entity, category, or city.
     *
     * This test checks if companies matching a specific search term in the name, CUIT, sector, or other fields
     * are retrieved and displayed to the user. It also verifies that companies that do not match the search term
     * are not displayed, confirming the correct implementation of the search functionality.
     *
      @test
      @lautarovg02
     */
    public function it_allows_search_by_relevant_company_criteria()
    {
        // Set up - create provinces, cities and companies
        $province = Province::factory()->create();
        $city = City::factory()->create(['name' => 'TestCity']);
        $company1 = Company::factory()->create([
            'denomination' => 'TestDenomination',
            'cuit' => 20999999,
            'company_name' => 'TestCompany',
            'sector' => 'Technology',
            'entity' => 'Private',
            'company_category' => 'Large',
            'city_id' => $city->id,
        ]);

        $company2 = Company::factory()->create([
            'denomination' => 'NonMatchingCompany',
            'cuit' => 20992999,
            'company_name' => 'AnotherCompany',
            'sector' => 'Agriculture',
            'entity' => 'Public',
            'company_category' => 'Small',
            'city_id' => $city->id,
        ]);

        // Act - perform search for a matching term
        $response = $this->get('/companies?search=TestDenomination');

        // Assert - check that only matching companies are visible
        $response->assertStatus(200);
        $response->assertSeeText($company1->company_name);
        $response->assertDontSeeText($company2->company_name);

        // Additional test for searching by CUIT
        $response = $this->get('/companies?search=20992999');
        $response->assertSeeText($company2->company_name);
        $response->assertDontSeeText($company1->company_name);

        // Additional test for searching by city name
        $response = $this->get('/companies?search=TestCity');
        $response->assertSeeText($company1->company_name);
        $response->assertSeeText($company2->company_name);

        // Additional test for searching by sector
        $response = $this->get('/companies?search=Technology');
        $response->assertSeeText($company1->company_name);
        $response->assertDontSeeText($company2->company_name);

        // Additional test for searching by company_category
        $response = $this->get('/companies?search=Large');
        $response->assertSeeText($company1->company_name);
        $response->assertDontSeeText($company2->company_name);
    }

    /**
     * Test that searching with a partial term returns matching companies.
     *
     * This test ensures that users can search with partial terms and still get relevant results.
     *
      @test
      @lautarovg02
     */
    public function test_partial_search_term_returns_matching_companies()
    {
        // Set up - create provinces, cities and companies
        $province = Province::factory()->create();
        $city = City::factory()->create(['name' => 'TestCity']);
        // Arrange: Create a company with a recognizable name part
        $company = Company::factory()->create(['company_name' => 'Tech Solutions']);

        // Act: Perform a search with a partial term of the company name
        $response = $this->get('/companies?search=Tech');

        // Assert: Check that the response contains the company with partial matching term
        $response->assertStatus(200);
        $response->assertSeeText('Tech Solutions');
    }

    /**
     * Test that searching is case-insensitive.
     *
     * This test verifies that a search term in different cases returns the expected company results.
     *
      @test
      @lautarovg02
     */
    public function test_search_is_case_insensitive()
    {
        // Set up - create provinces, cities and companies
        $province = Province::factory()->create();
        $city = City::factory()->create(['name' => 'TestCity']);
        // Arrange: Create a company with a mixed-case name
        $company = Company::factory()->create(['company_name' => 'Technology Innovators']);

        // Act: Search with lowercase term
        $response = $this->get('/companies?search=technology');

        // Assert: The company should still appear in search results
        $response->assertStatus(200);
        $response->assertSeeText('Technology Innovators');
    }

    /**
     * Test that the search functionality is protected from SQL injection.
     *
     * This test provides a potentially malicious input and checks that the application does not fail.
     *
      @test
      @lautarovg02
     */
    public function test_sql_injection_protection()
    {
        // Act: Perform a search with SQL injection pattern
        $response = $this->get('/companies?search=\' OR 1=1; -- ');

        // Assert: Check that the response is successful and shows no unexpected data
        $response->assertStatus(200);
        $response->assertDontSeeText('All Companies'); // Example to ensure no unwanted data is leaked
    }


    /**
     * Test that searching with multiple terms returns matching companies.
     *
     * This test verifies that combining search criteria, like sector and city name, yields correct results.
     *
      @test
      @lautarovg02
     */
    public function test_multiple_search_terms()
    {
        // Set up - create provinces, cities and companies
        $province = Province::factory()->create();
        $city = City::factory()->create(['name' => 'New York']);
        // Arrange: Create companies with distinct characteristics
        $company = Company::factory()->create([
            'company_name' => 'Innovatech',
            'sector' => 'Technology',
            'city_id' => $city->id,
        ]);

        // Act: Search using multiple terms that match the sector and city
        $response = $this->get('/companies?search=Technology New York');

        // Assert: Confirm that the search results include the expected company
        $response->assertStatus(200);
        $response->assertSeeText('Innovatech');
    }

    /**
     * Test that searching with no matching results displays a "No results found" message.
     *
     * This test ensures that users receive feedback if no companies match their search criteria.
     *
      @test
      @lautarovg02
     */
    public function test_no_results_found_displays_message()
    {
        // Act: Perform a search with a term that does not match any company
        $response = $this->get('/companies?search=XYZ123');

        // Assert: Check that a "No se encontraron resultados para " message is displayed
        $response->assertStatus(200);
        $response->assertSeeText('No se encontraron resultados para ');
    }
}
