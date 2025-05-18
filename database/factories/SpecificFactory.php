<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contract;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specific>
 */
class SpecificFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => Contract::factory(),
            'signing_date' => $this->faker->date(),
            'objective' => $this->faker->text(100),
            'commitment_parties' => $this->faker->text(),
            'responsable_control_company' => $this->faker->name(),
            'responsable_control_fio' => $this->faker->name(),
            'file' => null,
        ];
    }
}
