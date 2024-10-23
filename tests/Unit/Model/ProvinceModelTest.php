<?php

namespace Tests\Unit\Model;

use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ProvinceModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Province model has the fillable property defined.
        @dairagalceran
        @test
     */
    public function test_province_model_has_fillable_property()
    {
        $fillable = (new Province())->getFillable();

        $expectedFillable = ['name', ];
        foreach ($expectedFillable as $field) {
            $this->assertTrue(in_array($field, $fillable), "The field {$field} is not in the fillable property of the Province model.");
        }
    }
}
