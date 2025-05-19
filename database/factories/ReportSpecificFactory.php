<?php

namespace Database\Factories;

use App\Models\Specific;
use App\Models\Type_Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\report_specific>
 */
class ReportSpecificFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'specific_id' => Specific::inRandomOrder()->first()->id,
            'specific_contract_id' => Specific::inRandomOrder()->first()->id,
            'upload_date' => $this->faker->date(),
            'type' => $this->faker->randomDigit(),
            'url_report' => $this->faker->url(),
            'type_report_id' => Type_Report::inRandomOrder()->first()->id,
            'file' => random_bytes(256), // 256 bytes binarios aleatorios
        ];
    }
}
