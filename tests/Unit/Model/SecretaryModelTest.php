<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Secretary;


class SecretaryModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Secretary model has the fillable property defined.
     @lautarovg02
     @test
     */
    public function test_model_has_fillable_property()
    {
        $fillable = (new Secretary())->getFillable();

        $expectedFillable = ['user_name', 'email', 'password']; // Adjust according to the necessary fields
        foreach ($expectedFillable as $field) {
            $this->assertTrue(in_array($field, $fillable), "The field {$field} is not in the fillable property of the Secretary model.");
        }
    }
}
