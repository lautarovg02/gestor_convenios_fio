<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CareerModelTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Testea que el método scopeSearchAndSort devuelva los resultados correctos.
     *
      @test
      @lautarov02
     */
    public function testSearchAndSortReturnsCorrectResults()
    {
        Teacher::factory()->count(10)->create();

        // Crea algunos datos de prueba
        $department = Department::create(['name' => 'Software Engineering']);
        $career1 = Career::create(['name' => 'Computer Science', 'department_id' => $department->id]);
        $career2 = Career::create(['name' => 'Information Technology', 'department_id' => $department->id]);
        $career3 = Career::create(['name' => 'Software Development', 'department_id' => $department->id]);

        // Prueba la búsqueda
        $careers = Career::searchAndSort('Computer', 'name', 'asc')->get();
        $this->assertCount(1, $careers);
        $this->assertEquals($career1->id, $careers[0]->id);

        // Prueba el ordenamiento
        $careers = Career::searchAndSort(null, 'name', 'asc')->get();
        $this->assertEquals($career1->id, $careers[0]->id);
        $this->assertEquals($career2->id, $careers[1]->id);
        $this->assertEquals($career3->id, $careers[2]->id);

        // Prueba el ordenamiento descendente
        $careers = Career::searchAndSort(null, 'name', 'desc')->get();
        $this->assertEquals($career3->id, $careers[0]->id);
        $this->assertEquals($career2->id, $careers[1]->id);
        $this->assertEquals($career1->id, $careers[2]->id);
    }
}
