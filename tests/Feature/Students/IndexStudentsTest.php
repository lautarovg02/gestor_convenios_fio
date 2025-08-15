<?php

namespace Tests\Feature\Students;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexStudentsTest extends TestCase
{
    use RefreshDatabase;

    // Cambiá este texto si tu vista usa otro mensaje
    private const EMPTY_TEXT = 'No hay estudiantes registrados.';

    public function test_muestra_listado_con_estudiantes_cuando_existen(): void
    {
        // Creamos dos alumnos con campos reales
        $s1 = Student::factory()->create([
            'name'       => 'Juan',
            'last_name'  => 'Pérez',
            'dni'        => 30111222,
            'career'     => 'Ingeniería Industrial',
        ]);

        $s2 = Student::factory()->create([
            'name'       => 'Ana',
            'last_name'  => 'García',
            'dni'        => 40123456,
            'career'     => 'Ing. Sistemas',
        ]);

        $resp = $this->get(route('students.index'));

        $resp->assertOk()
             // Datos básicos (criterio)
             ->assertSee('Juan')
             ->assertSee('Pérez')
             ->assertSee('30111222')
             ->assertSee('Ingeniería Industrial')
             ->assertSee('Ana')
             ->assertSee('García')
             ->assertSee('40123456')
             ->assertSee('Ing. Sistemas')
             // No dependo del texto del botón: verifico que exista el link al show
             ->assertSee(route('students.show', $s1))
             ->assertSee(route('students.show', $s2));
    }

    public function test_muestra_mensaje_cuando_no_hay_estudiantes(): void
    {
        $resp = $this->get(route('students.index'));
    
        $resp->assertOk()
             ->assertSeeText('¡La tabla de alumnos está vacía!');
    }
    
}
