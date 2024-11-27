<?php

namespace Tests\Feature\Teacher;

use App\Models\Career;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * @dairagalceran
    */
    public function it_loads_the_edit_page_with_teacher_data()
    {
        // Crear un docente de prueba
        $teacher = Teacher::factory()->create();

        // Realizar la solicitud al método edit
        $response = $this->get(route('teachers.edit', $teacher->id));

        // Verificar que la vista cargue correctamente
        $response->assertStatus(200);
        $response->assertViewIs('teachers.edit');
        $response->assertViewHas('teacher', function ($viewTeacher) use ($teacher) {
            return $viewTeacher->id === $teacher->id;
        });
}

}
