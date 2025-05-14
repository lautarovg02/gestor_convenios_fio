<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'dni' => $this->faker->unique()->numberBetween(10000000, 60000000),
            'cuil' => $this->faker->unique()->numberBetween(10000000000, 60000000000),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_numb' => $this->faker->unique()->numberBetween(2284000000, 7800000000),
            'career' => $this->faker->randomElement(['Ingeniería Civil',
                                                     'Ingeniería en Agrimensura',
                                                     'Ingeniería Electromecánica',
                                                     'Ingeniería Industrial',
                                                     'Ingeniería Química',
                                                     'Ingeniería en Sistemas',
                                                     'Licenciatura en Tecnología Médica',
                                                     'Licenciatura en Tecnología de los Alimentos',
                                                     'Ingeniería en Seguridad e Higiene en el Trabajo'])
        ];
    }
}
