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
            // Solo crear si no tiene perfil ya
            Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'      => fake()->firstName(),
                    'lastname'  => fake()->lastName(),
                    'dni'       => fake()->unique()->randomNumber(8),
                    'is_rector' => false,
                    'is_dean'   => false,
                ]
            );
        }
    }
}
