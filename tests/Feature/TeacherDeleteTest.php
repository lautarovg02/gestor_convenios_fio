<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que no se pueda eliminar un profesor con el rol de director, coordinador o si es decano.
     *
     * @test
     * @rom-L
     */
    public function it_does_not_allow_deletion_if_teacher_has_roles_or_is_dean()
    {
        // Crear un profesor con el rol de Director
        $teacherAsDirector = Teacher::factory()->create();
        Department::create(['name' => 'Mathematics', 'director_id' => $teacherAsDirector->id]);

        // Intentar eliminar al profesor con rol de Director
        $responseDirector = $this->delete(route('teachers.destroy', $teacherAsDirector));
        $responseDirector->assertRedirect(route('teachers.index'));

        // Verificar que el profesor sigue existiendo en la base de datos
        $this->assertDatabaseHas('teachers', ['id' => $teacherAsDirector->id]);

        // Crear un profesor que sea Decano
        $teacherAsDean = Teacher::factory()->create(['is_dean' => true]);

        // Intentar eliminar al profesor que es Decano
        $responseDean = $this->delete(route('teachers.destroy', $teacherAsDean));
        $responseDean->assertRedirect(route('teachers.index'));


        // Verificar que el profesor sigue existiendo en la base de datos
        $this->assertDatabaseHas('teachers', ['id' => $teacherAsDean->id]);
    }



    /**
     * Verifica que un profesor sin roles pueda ser eliminado correctamente.
     *
     * @test
     * @rom-L
     */
    public function it_allows_deletion_if_teacher_has_no_roles()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->delete(route('teachers.destroy', $teacher));

        $response->assertRedirect(route('teachers.index'));
        $response->assertSessionHas('success', function ($message) use ($teacher) {
            return str_contains($message, $teacher->name);
        });

        // Ensure teacher is deleted
        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);
    }
}
