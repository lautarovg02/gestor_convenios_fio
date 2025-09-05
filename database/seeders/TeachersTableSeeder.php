<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;

class TeachersTableSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role_id', 2)->get();

        foreach ($teachers as $user) {
            Teacher::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
