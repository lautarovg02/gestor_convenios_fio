<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Province>
 */
class ProvinceFactory extends Factory
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
                'Buenos Aires',
                'Catamarca',
                'Chaco',
                'Chubut',
                'Córdoba',
                'Corrientes',
                'Entre Ríos',
                'Formosa',
                'Jujuy',
                'La Pampa',
                'La Rioja',
                'Mendoza',
                'Misiones',
                'Neuquén',
                'Río Negro',
                'Salta',
                'San Juan',
                'San Luis',
                'Santa Cruz',
                'Santa Fe',
                'Santiago del Estero',
                'Tierra del Fuego',
                'Tucumán',
            ]),
        ];
    }
}
