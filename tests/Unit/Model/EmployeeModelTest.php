<?php

namespace Tests\Unit\Model;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class EmployeeModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Employee model has the fillable property defined.
    @dairagalceran
    @test
     */
    public function test_employee_model_has_fillable_property()
    {
        $fillable = (new Employee())->getFillable();

        $expectedFillable = ['name', 'lastname', 'dni', 'cuil' , 'email', 'position','is_represent', 'company_id']; // Adjust according to the necessary fields
        foreach ($expectedFillable as $field) {
            $this->assertTrue(in_array($field, $fillable), "The field {$field} is not in the fillable property of the Company model.");
        }
    }
}
