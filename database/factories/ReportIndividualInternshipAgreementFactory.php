<?php

namespace Database\Factories;

use App\Models\IndividualInternshipAgreement;
use App\Models\Type_Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportIndividualIntershipAgreement>
 */
class ReportIndividualInternshipAgreementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $individual = IndividualInternshipAgreement::inRandomOrder()->first();
        return [
            'individual_internship_id' => $individual->id,
            'individual_internship_contract_id' => $individual->contract_id,
            'upload_date' => $this->faker->date(),
            'url_report' => $this->faker->url(),
            'type_report_id' => Type_Report::inRandomOrder()->first()->id,
            'file' => random_bytes(256), // 256 bytes binarios aleatorios,
        ];
    }
}
