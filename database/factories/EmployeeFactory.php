<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use Faker\Provider\en_US\Person; // Importa el proveedor
use Faker\Provider\en_US\Company as FakerCompany;
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
        $this->faker->addProvider(new Person($this->faker)); // Agrega proveedor de Person
        $this->faker->addProvider(new FakerCompany($this->faker)); // Agrega proveedor de Company


        return [
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'dni' => $this->faker->unique()->numberBetween(10000000, 60000000),
            'cuil' => $this->faker->unique()->numberBetween(10000000000, 60000000000),
            'email' => $this->faker->unique()->email(),
            'position' => $this->faker->jobTitle(),
            'is_represent' => $this->faker->boolean,
            'company_id' => Company::inRandomOrder()->first()->id, // Crea un empleado asociado a una empresa
        ];
    }
}
