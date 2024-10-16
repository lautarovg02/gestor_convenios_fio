<?php

namespace Database\Factories;

use App\Models\Secretary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecretaryPhone>
 */
class SecretaryPhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => $this->faker->numberBetween(2284000000, 7800000000),
            'secretary_id' => Secretary::inRandomOrder()->first()->id,
        ];
    }
}
