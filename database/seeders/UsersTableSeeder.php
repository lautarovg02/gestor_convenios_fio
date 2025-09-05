<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role_id' => 3,
            'password' => bcrypt('password'),
        ]);

        // Secretary
        User::factory()->create([
            'name' => 'Maria Secretaria',
            'email' => 'secretary@test.com',
            'role_id' => 1,
            'password' => bcrypt('password'),
        ]);

        // Teacher
        User::factory()->create([
            'name' => 'Juan Teacher',
            'email' => 'teacher@test.com',
            'role_id' => 2,
            'password' => bcrypt('password'),
        ]);
    }
}
