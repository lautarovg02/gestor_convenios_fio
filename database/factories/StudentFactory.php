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
            'dni' => $this->faker->unique()->numberBetween(1000000, 99999999), // 7 u 8 dígitos
            'cuil' => $this->faker->unique()->numerify('20##########'), // 12 dígitos con prefijo fijo '20' (podés ajustar)
            'email' => $this->faker->unique()->safeEmail(),
            'phone_numb' => $this->faker->numerify('2284#######'), // puede ser nulo o número
            'career' => $this->faker->randomElement([
                'Ingeniería Civil',
                'Ingeniería en Agrimensura',
                'Ingeniería Electromecánica',
                'Ingeniería Industrial',
                'Ingeniería Química',
                'Ingeniería en Sistemas',
                'Licenciatura en Tecnología Médica',
                'Licenciatura en Tecnología de los Alimentos',
                'Ingeniería en Seguridad e Higiene en el Trabajo'
            ]),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->numberBetween(1, 5000),
            'city' => $this->faker->city(),
        ];
    }
}