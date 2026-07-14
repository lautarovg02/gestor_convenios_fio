<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Asegurar que los roles existan
        $this->call(RolePermissionSeeder::class);

        // 2. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                // 'role_id' => 3, <--- ¡ELIMINADO! Ya no existe esta columna
            ]
        );
        $admin->assignRole('admin');

        // 3. Secretary
        $secretary = User::firstOrCreate(
            ['email' => 'secretary@test.com'],
            [
                'name' => 'Maria Secretaria',
                'password' => bcrypt('password'),
                // 'role_id' => 1, <--- ¡ELIMINADO!
            ]
        );
        // IMPORTANTE: Asegúrate de haber agregado 'secretary' en RolePermissionSeeder
        // Si no lo agregaste, cambia esto por 'user' o 'admin'
        $secretary->assignRole('secretary'); 

        // 4. Teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name' => 'Juan Teacher',
                'password' => bcrypt('password'),
                // 'role_id' => 2, <--- ¡ELIMINADO!
            ]
        );
        // IMPORTANTE: Asegúrate de haber agregado 'teacher' en RolePermissionSeeder
        $teacher->assignRole('teacher');

        // 5. Director
        $director = User::firstOrCreate(
            ['email' => 'director@test.com'],
            [
                'name' => 'Juan Director',
                'password' => bcrypt('password'),
                // 'role_id' => 1, <--- ¡ELIMINADO!
            ]
        );
        $director->assignRole('Director');

        // 6. Coordinador
        $coordinador = User::firstOrCreate(
            ['email' => 'coordinador@test.com'],
            [
                'name' => 'Maria Coordinadora',
                'password' => bcrypt('password'),
                // 'role_id' => 1, <--- ¡ELIMINADO!
            ]
        );
        $coordinador->assignRole('Coordinador');
    }
}