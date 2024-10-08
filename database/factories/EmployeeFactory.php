<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'dni' => $this->faker->unique()->numberBetween(10000000, 60000000),
            'email' => $this->faker->unique()->safeEmail,
            'position' => $this->faker->word,
            'is_represent' => $this->faker->boolean,
            'company_id' => Company::inRandomOrder()->first()->id, // Crea un empleado asociado a una empresa
        ];
    }
}
