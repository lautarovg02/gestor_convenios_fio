<?php

namespace Tests\Feature\Students;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_elimina_un_estudiante(): void
    {
        // NOT NULL en tu esquema
        $student = Student::factory()->create([
            'phone_numb' => '2264000000',
        ]);

        $resp = $this->delete(route('students.destroy', $student));

        // Redirige (no asumimos destino exacto)
        $resp->assertStatus(302);

        // Ya no debe existir
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
