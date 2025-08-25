<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ContractStatus;
use App\Models\Employee;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    public function definition(): array
    {
        return [
            'signing_date'                 => $this->faker->date(),
            'url_certificate_afip'         => $this->faker->url(),
            'url_statute'                  => $this->faker->url(),
            'url_assignment_authorities'   => $this->faker->url(),

            // 👉 Estas líneas crean los relacionados si no los pasás explícitamente en create()
            'company_id'                   => Company::factory(),
            'secretary_id'                 => Secretary::factory(),
            'teacher_id'                   => Teacher::factory(),
            'rector'                       => Teacher::factory()->state(['is_rector' => true]),
            'contract_status_id'           => ContractStatus::factory(),
            'type_framework_agreement_id'  => TypeFrameworkAgreement::factory(),

            'creation_date'                => $this->faker->date(),
            // Empleados se crean si no los seteás desde el test
            'contact_employee_id'          => Employee::factory(),
            'representative_employee_id'   => Employee::factory(),

            'file'                         => random_bytes(256),
        ];
    }

    /**
     * (Opcional) Helper para asegurar que todos los IDs pertenezcan a la MISMA empresa.
     * Útil si tu dominio exige consistencia de company_id.
     */
    public function forCompany(Company $company): self
    {
        return $this->state(fn () => [
            'company_id' => $company->id,
            'contact_employee_id' => Employee::factory()->state(['company_id' => $company->id]),
            'representative_employee_id' => Employee::factory()->state(['company_id' => $company->id]),
            'secretary_id' => Secretary::factory(), // si Secretary/Teacher no dependen de company, dejalos así
            'teacher_id' => Teacher::factory(),
            'rector' => Teacher::factory()->state(['is_rector' => true]),
        ]);
    }
}
