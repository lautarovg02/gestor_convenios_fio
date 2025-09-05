<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Secretary;

class SecretariesTableSeeder extends Seeder
{
    public function run(): void
    {
        $secretaries = User::where('role_id', 1)->get();

        foreach ($secretaries as $user) {
            Secretary::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
