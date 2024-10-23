<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;

class CityControllerTest extends TestCase
{
    /**
     * Test to ensure that CityController has all CRUD methods defined.
        @dairagalceran
        @test
     */
    public function test_city_controller_has_crud_methods()
    {
    $methods = ['index','create','store','show','edit','update','destroy'];

        // We used reflection to get the methods
        $controllerMethods = get_class_methods(\App\Http\Controllers\CityController::class);

        foreach ($methods as $method) {
            $this->assertTrue(in_array($method, $controllerMethods), "City controller doesn't have the method: {$method}");
        }
    }
}
