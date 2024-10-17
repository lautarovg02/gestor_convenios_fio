<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SecretaryControllerTest extends TestCase
{
  /**
     * Test to ensure that the SecretaryController has all CRUD methods defined.
     @lautarovg02
     @test
     */
    public function test_controller_has_crud_methods()
    {
    $methods = ['index','create','store','show','edit','update','destroy'];

        // We used reflection to get the methods
        $controllerMethods = get_class_methods(\App\Http\Controllers\SecretaryController::class);

        foreach ($methods as $method) {
            $this->assertTrue(in_array($method, $controllerMethods), "Secretary controller doesn't have the method: {$method}");
        }
    }
}
