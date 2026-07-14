<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Secretary;

class SecretariesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Usar scope de Spatie para obtener usuarios con rol Secretaria
        $users = \App\Models\User::role('Secretaria')->get();

        foreach ($users as $user) {
            Secretary::firstOrCreate(['user_id' => $user->id]);
        }
    }
}
