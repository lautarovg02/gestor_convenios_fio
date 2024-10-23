<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;

class ProvinceControllerTest extends TestCase
{
    /**
     * Test to ensure that ProvinceController has all CRUD methods defined.
        @dairagalceran
        @test
     */
    public function test_province_controller_has_crud_methods()
    {
    $methods = ['index','create','store','show','edit','update','destroy'];

        // We used reflection to get the methods
        $controllerMethods = get_class_methods(\App\Http\Controllers\ProvinceController::class);

        foreach ($methods as $method) {
            $this->assertTrue(in_array($method, $controllerMethods), "Province controller doesn't have the method: {$method}");
        }
    }
}
