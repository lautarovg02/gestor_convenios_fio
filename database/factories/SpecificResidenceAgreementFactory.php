<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contract;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpecificResidenceAgreement>
 */
class SpecificResidenceAgreementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'internship_initial_date' => $this->faker->date(),
            'task' => $this->faker->text(),
            'signing_date' => $this->faker->date(),
            'contract_id' => Contract::factory(),
            'student_id' => Student::factory(),
            'file' => null,
        ];
    }
}
