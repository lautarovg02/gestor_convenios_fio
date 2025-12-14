<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secretary>
 */
class SecretaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Borramos 'username' porque ya no existe en la BD.
            
            // Definimos que por defecto cree un usuario nuevo si no se le pasa uno.
            'user_id' => User::factory(),
        ];
    }
}