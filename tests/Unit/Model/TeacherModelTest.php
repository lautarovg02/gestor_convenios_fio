<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Career;

use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherModelTest extends TestCase
{

    use RefreshDatabase;

    /**
      @test
      @lautarvg02
     * Test that the getTeachersWithoutRoles method returns only teachers
     * who are not directors of departments or coordinators of any careers.
     */
    public function test_get_teachers_without_roles_returns_valid_teachers()
    {
        // Crear docentes con y sin roles
        $teacherWithoutRoles = Teacher::factory()->create();
        $teacherWithRole = Teacher::factory()->create();

        Department::factory()->create([
            'name' => 'Departamento de Ingenieria',
            'director_id' => $teacherWithRole->id
        ]);

        // Llamar al método y obtener los resultados
        $result =  Teacher::getTeachersWithoutRoles()->orderBy('name', 'ASC')->get();

        // Verificar que solo los docentes sin roles sean devueltos
        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($teacherWithoutRoles));
        $this->assertFalse($result->contains($teacherWithRole));
    }
}
