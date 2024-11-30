<?php

namespace Tests\Feature\Teacher;

use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherFilterTest extends TestCase
{

    use RefreshDatabase;

 /*    public function test_search_by_career_returns_correct_results()
    {
        // Arrange
        $teachers = Teacher::factory()->count(3)->create();
        $department = Department::factory()->create();

// Relación con el departamento (ajusta según la relación real)
$department->teacher()->associate($teachers[2]);
$department->save();

        $career = Career::factory()->create();
        $career1 = Career::factory()->create();

        $career->teachers()->attach($teachers[0]->id);
        $career1->teachers()->attach($teachers[1]->id);
        //Debug para verificar los datos
        dump($teachers->toArray());
        dump($career->toArray());
        dump($career1->toArray());

        // Act
        $response = $this->get(route('teachers.index', [
            'career' => $career->name,
        ]));
 // Debug para inspeccionar el HTML de respuesta
 dump($response->getContent());
        // Assert
        $response->assertStatus(200);
        $response->assertSee($career->name);
        $response->assertSee($teachers[0]->name); // Verificar que el docente relacionado a la carrera esté presente
        $response->assertDontSee($teachers[1]->name); // Verificar que docentes no relacionados no estén presentes
        $response->assertDontSee($teachers[2]->name);
    }
 */
    public function test_search_by_career_returns_correct_results()
    {
        // Arrange
        $teachers = Teacher::factory()->count(3)->create();
        $department = Department::factory()->create();

        $department->teacher()->associate($teachers[2]);
        $department->save();

        $career = Career::factory()->create();
        $career1 = Career::factory()->create();

        $career->teachers()->attach($teachers[0]->id);
        $career1->teachers()->attach($teachers[1]->id);

        // Debug para verificar los datos
        dump($teachers->toArray());
        dump($career->toArray());

        // Act
        $response = $this->get(route('teachers.index', [
            'career' => $career->name,
        ]));

        // Debug para inspeccionar el HTML de respuesta
        dump($response->getContent());

        // Assert
        $response->assertStatus(200);
        $response->assertSee($career->name);
        $response->assertSee($teachers[0]->name);
        $response->assertDontSee($teachers[1]->name);
        $response->assertDontSee($teachers[2]->name);
    }

}
