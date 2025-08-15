<?php

namespace Tests\Feature\Students;

use Tests\TestCase;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_permite_crear_un_estudiante_valido(): void
    {
        $payload = [
            'name'       => 'Ana',
            'last_name'  => 'García',
            'dni'        => 40123456,
            'career'     => 'Ing. Sistemas',
            // Campos que tu request está pidiendo como requeridos:
            'cuil'       => '20123456789',
            'email'      => 'ana@example.com',
            'street'     => 'Mitre',
            'number'     => 1234,
            'city'       => 'Olavarría',
            // Si tu request también pide phone_numb, descomentalo:
             'phone_numb' => 2264123456,
        ];

        $resp = $this->post(route('students.store'), $payload);

        // Tu app redirige a '/', así que no asumimos students.index
        $resp->assertStatus(302);
        // Si querés afirmarlo explícitamente:
        // $resp->assertRedirect('/');

        $this->assertDatabaseHas('students', [
            'name'      => 'Ana',
            'last_name' => 'García',
            'dni'       => 40123456,
            'career'    => 'Ing. Sistemas',
            'cuil'      => '20123456789',
            'email'     => 'ana@example.com',
            // 'phone_numb' => 2264123456, // solo si lo estás guardando
        ]);
    }

    public function test_valida_campos_requeridos_basicos(): void
    {
        $payload = [
            'name'       => '',
            'last_name'  => '',
            'dni'        => '',  // requerido por tu request
            // 'career' => '',    // si también lo pedís, agregalo acá
        ];

        $resp = $this->post(route('students.store'), $payload);

        $resp->assertSessionHasErrors(['name', 'last_name', 'dni']);
    }
}
