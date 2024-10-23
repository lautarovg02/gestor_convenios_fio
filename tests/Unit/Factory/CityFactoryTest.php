<?php

namespace Tests\Unit\Factory;

use App\Models\City;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityFactoryTest extends TestCase
{
    use RefreshDatabase;

   /**
     * Test that the factory creates a City with valid attributes.
        @dairagalceran
        @test
     */
    public function test_city_factory_creates_valid_city()
    {
        //Create a city
        Province::factory()->create();
        $city = City::factory()->create();

        $this->assertNotNull($city);
        $this->assertDatabaseHas('cities', [
            'name' => $city->name,
            'province_id' => $city->province_id,
        ]);
    }


    /**
     * Test that the factory has all necessary attributes defined.
        @dairagalceran
        @test
     */
    public function test_city_factory_has_required_attributes()
    {
        Province::factory()->create();
        $city = City::factory()->create();

        $this->assertNotNull($city->name);
        $this->assertNotNull($city->province_id);
    }

     /**
     * Test that the factory generates realistic data.
        @dairaGalceran
        @test
     */
    public function test_factory_generates_realistic_data()
    {
        Province::factory()->create();
        $city = City::factory()->make();

        $this->assertIsString($city->name);
        $this->assertIsInt($city->province_id);
    }
}
