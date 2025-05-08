<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ContractStatus;
use App\Models\Employee;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        
        return [
            'signing_date' => $this->faker->date(),
            'url_certificate_afip' =>$this->faker->url(),
            'url_statute' =>$this->faker->url(),
            'url_assignment_authorities' =>$this->faker->url(),
            'company_id' => Company::inRandomOrder()->first()->id,
            'secretary_id' => Secretary::inRandomOrder()->first()->id,
            'teacher_id' => Teacher::inRandomOrder()->first()->id,
            'creation_date' =>$this->faker->date(),
            'contact_employee_id' => Employee::inRandomOrder()->first()->id,
            'representative_employee_id' => Employee::inRandomOrder()->first()->id,
            'rector' => Teacher::inRandomOrder()->first()->id,
            'contract_status_id' => ContractStatus::inRandomOrder()->first()->id,
            'type_framework_agreement_id' => TypeFrameworkAgreement::inRandomOrder()->first()->id,
            'file' => random_bytes(256), // 256 bytes binarios aleatorios
        ];
    }
}
