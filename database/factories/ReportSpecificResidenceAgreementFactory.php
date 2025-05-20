<?php

namespace Database\Factories;

use App\Models\SpecificResidenceAgreement;
use App\Models\Type_Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportSpecificResidenceAgreement>
 */
class ReportSpecificResidenceAgreementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $specific = SpecificResidenceAgreement::inRandomOrder()->first();
             return [
            'specific_residence_agreement_id' => $specific->id,
            'specific_residence_agreement_contract_id' => $specific->contract_id,
            'upload_date' => $this->faker->date(),
            'url_report' => $this->faker->url(),
            'type_report_id' => Type_Report::inRandomOrder()->first()->id,
            'file' => random_bytes(256), // 256 bytes binarios aleatorios,
        ];
    }
}
