<?php

namespace Tests\Unit\Model;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CityModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the City model has the fillable property defined.
    @dairagalceran
    @test
     */
    public function test_city_model_has_fillable_property()
    {
        $fillable = (new City())->getFillable();

        $expectedFillable = ['name', 'province_id'];
        foreach ($expectedFillable as $field) {
            $this->assertTrue(in_array($field, $fillable), "The field {$field} is not in the fillable property of the City model.");
        }
    }
}

