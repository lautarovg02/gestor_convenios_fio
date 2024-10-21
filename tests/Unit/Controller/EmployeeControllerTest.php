<?php

namespace Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;

class EmployeeControllerTest extends TestCase
{
   /**
     * Test to ensure that EmployeeController has all CRUD methods defined.
     @dairagalceran
     @test
     */
    public function test_employee_controller_has_crud_methods()
    {
    $methods = ['index','create','store','show','edit','update','destroy'];

        // We used reflection to get the methods
        $controllerMethods = get_class_methods(\App\Http\Controllers\EmployeeController::class);

        foreach ($methods as $method) {
            $this->assertTrue(in_array($method, $controllerMethods), "Employee controller doesn't have the method: {$method}");
        }
    }
}
