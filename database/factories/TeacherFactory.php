<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            // 'user_id' => se setea en el seeder
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'dni' => $this->faker->unique()->numberBetween(10000000, 70000000),
            'cuil' => $this->faker->numberBetween(10000000, 50000000),
            'faculty' => $this->faker->randomElement(['FIO', 'FCE', 'FCH', null]),
            'is_rector' => false,
            'is_dean' => false,
        ];
    }
}
