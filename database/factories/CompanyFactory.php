<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Definir el nombre de la compañía
        $companyName = $this->faker->company;

        return [
            'denomination' => 'Denominacion nro: ' . $this->faker->randomNumber(2),
            'cuit' => $this->faker->unique()->numberBetween(20000000000, 90999999000),
            'company_name' => $companyName,
            'sector' => $this->faker->word,
            'entity' => $this->faker->word,
            'company_category' => $this->faker->word,
            'scope' => $this->faker->randomElement(['NACIONAL', 'INTERNACIONAL']), // Seleccionar entre Nacional o Internacional
            'street' => $this->faker->streetName,
            'number' => $this->faker->numberBetween(1, 1000),
            'city_id' => City::inRandomOrder()->first()->id, // Asegúrate de que los IDs de ciudad existan
            'slug' => Str::slug($companyName),  // Generar un slug basado en el nombre de la compañía
        ];
    }
}
