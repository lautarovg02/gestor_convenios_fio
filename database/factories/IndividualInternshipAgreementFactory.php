<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contract;
use App\Models\Student;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IndividualInternshipAgreement>
 */
class IndividualInternshipAgreementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'months_quantity' => $this->faker->numberBetween(1, 12),
            'task' => $this->faker->text(),
            'internship_initial_date' => $this->faker->date(),
            'assignment' => $this->faker->word(),
            'area' => $this->faker->word(),
            'signing_date' => $this->faker->date(),
            'contract_id' => Contract::factory(),
            'student_id' => Student::factory(),
            'file' => null,
        ];
    }
}
