<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Ingeniería Civil y Agrimensura',
                'Ingeniería Química',
                'Ingeniería Electromecánica',
                'Ingeniería en Seguridad e Higiene en el Trabajo',
            ]),

            'director_id' => Teacher::inRandomOrder()->first()->id, // DEBEMOS AGRAGAR EN teacher is_department_director
        ];
    }
}
