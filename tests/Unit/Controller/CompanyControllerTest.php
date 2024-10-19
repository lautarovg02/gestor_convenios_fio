<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CompanyControllerTest extends TestCase
{
    /**
     * Test to ensure that CompanyController has all CRUD methods defined.
     @dairagalceran
     @test
     */
    public function test_company_controller_has_crud_methods()
    {
    $methods = ['index','create','store','show','edit','update','destroy'];

        // We used reflection to get the methods
        $controllerMethods = get_class_methods(\App\Http\Controllers\CompanyController::class);

        foreach ($methods as $method) {
            $this->assertTrue(in_array($method, $controllerMethods), "Company controller doesn't have the method: {$method}");
        }
    }
}
