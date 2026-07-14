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
        // Generar un contrato único por empresa y tipo
        $company = Company::inRandomOrder()->first() ?? Company::factory()->create();
        $existingTypes = \App\Models\Contract::where('company_id', $company->id)->pluck('type_framework_agreement_id')->toArray();
        $type = TypeFrameworkAgreement::whereNotIn('id', $existingTypes)->inRandomOrder()->first();
        
        if (!$type) {
            $company = Company::factory()->create();
            $type = TypeFrameworkAgreement::inRandomOrder()->first();
        }

        return [
            'signing_date' => $this->faker->date(),
            'url_certificate_afip' =>$this->faker->url(),
            'url_statute' =>$this->faker->url(),
            'url_assignment_authorities' =>$this->faker->url(),
            'company_id' => $company->id,
            'secretary_id' => Secretary::inRandomOrder()->first()->id ?? Secretary::factory(),
            'teacher_id' => Teacher::inRandomOrder()->first()->id ?? Teacher::factory(),
            'creation_date' =>$this->faker->date(),
            'contact_employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'representative_employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'rector' => Teacher::where('is_rector', true)->inRandomOrder()->first()->id ?? Teacher::factory()->create(['is_rector' => true])->id, 
            'contract_status_id' => ContractStatus::whereIn('status', ['SEVyT', 'SEVyT firma', 'En ejecución', 'Finalizado'])->inRandomOrder()->first()->id ?? ContractStatus::factory(),
            'type_framework_agreement_id' => $type->id,
            'file' => random_bytes(256), // 256 bytes binarios aleatorios
        ];
    }
}
