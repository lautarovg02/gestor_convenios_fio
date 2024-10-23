<?php

namespace Tests\Unit\Model;

use App\Models\EmployeePhone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class EmployeePhoneModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Employee model has the fillable property defined.
    @dairagalceran
    @test
     */
    public function test_employee_phone_model_has_fillable_property()
    {
        $fillable = (new EmployeePhone())->getFillable();

        $expectedFillable = ['phone_numb', 'employee_id']; // Adjust according to the necessary fields
        foreach ($expectedFillable as $field) {
            $this->assertTrue(in_array($field, $fillable), "The field {$field} is not in the fillable property of the EmployeePhone model.");
        }
    }
}
