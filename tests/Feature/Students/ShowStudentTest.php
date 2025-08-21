<?php

namespace Tests\Feature\Students;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_la_card_con_datos_basicos_y_carrera(): void
    {
        $student = Student::factory()->create([
            'name'       => 'Luca',
            'last_name'  => 'Romero',
            'dni'        => 38999111,
            'cuil'       => 20333999111,
            'email'      => 'luca@demo.com',
            'phone_numb' => 2264567890,
            'career'     => 'Ing. Sistemas',
            'street'     => 'Mitre',
            'number'     => 789,
            'city'       => 'Olavarría',
        ]);

        $resp = $this->get(route('students.show', $student));

        $resp->assertOk()
             ->assertSee('Luca')
             ->assertSee('Romero')
             ->assertSee('38999111')
             ->assertSee('Ing. Sistemas')
             // Si tu card muestra email/teléfono, también los afirmo
             ->assertSee('luca@demo.com')
             ->assertSee((string)$student->phone_numb);
    }
}
