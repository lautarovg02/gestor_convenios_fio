<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;

class TeachersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Usar scope de Spatie para obtener usuarios con rol Docente
        $teachers = \App\Models\User::role('Docente')->get();

        foreach ($teachers as $user) {
            $dni = fake()->unique()->randomNumber(8);
            // Solo crear si no tiene perfil ya
            Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'      => fake()->firstName(),
                    'lastname'  => fake()->lastName(),
                    'dni'       => $dni,
                    'cuil'      => '20' . str_pad($dni, 8, '0', STR_PAD_LEFT) . fake()->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]),
                    'is_rector' => false,
                    'is_dean'   => false,
                ]
            );
        }
    }
}
