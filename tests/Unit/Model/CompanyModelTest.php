<?php

namespace Tests\Unit\Model;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CompanyModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Company model has the fillable property defined.
    @dairagalceran
    @test
     */
    public function test_company_model_has_fillable_property()
    {
        $fillable = (new Company())->getFillable();

        $expectedFillable = ['denomination', 'cuit', 'company_name', 'sector' , 'entity', 'company_category','scope']; // Adjust according to the necessary fields
        foreach ($expectedFillable as $field) {
            $this->assertTrue(in_array($field, $fillable), "The field {$field} is not in the fillable property of the Company model.");
        }
    }
}
