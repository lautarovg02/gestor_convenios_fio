<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\Specific;
use App\Models\SpecificResidenceAgreement;
use App\Models\IndividualInternshipAgreement;
use App\Models\ContractStatus;

class TestContractsSeeder extends Seeder
{
    /**
     * Crea contratos de prueba de cada tipo para verificar los filtros por rol.
     * Usa datos existentes en la BD (company, secretary, teacher, employee, etc.)
     */
    public function run(): void
    {
        // Buscamos el primer estado que no sea Finalizado ni Deshabilitado
        // para que aparezcan en la lista de pendientes
        $status = ContractStatus::whereNotIn('status', ['Finalizado', 'Deshabilitado'])->first();

        $baseData = [
            'signing_date'                 => null,
            'url_certificate_afip'         => null,
            'url_statute'                  => null,
            'url_assignment_authorities'   => null,
            'company_id'                   => 1,
            'secretary_id'                 => 1,
            'teacher_id'                   => 1,
            'creation_date'                => now()->toDateString(),
            'contact_employee_id'          => 1,
            'representative_employee_id'   => null,
            'rector'                       => 1,
            'contract_status_id'           => $status->id,
            'type_framework_agreement_id'  => 1,
            'file'                         => null,
        ];

        // -------------------------------------------------------
        // 2 Convenios ESPECÍFICOS (para Director)
        // -------------------------------------------------------
        for ($i = 1; $i <= 2; $i++) {
            $contract = Contract::create(array_merge($baseData, [
                'creation_date' => now()->subDays($i)->toDateString(),
            ]));

            Specific::create([
                'contract_id'                => $contract->id,
                'signing_date'               => null,
                'objective'                  => "Objetivo del convenio específico de prueba #{$i}",
                'commitment_parties'         => "Compromisos de prueba #{$i}",
                'responsable_control_company'=> null,
                'responsable_control_fio'    => null,
                'file'                       => null,
            ]);

            $this->command->info("✅ Convenio Específico #{$i} creado (Contract ID: {$contract->id})");
        }

        // -------------------------------------------------------
        // 2 Convenios INDIVIDUAL DE RESIDENCIA (para Coordinador)
        // -------------------------------------------------------
        for ($i = 1; $i <= 2; $i++) {
            $contract = Contract::create(array_merge($baseData, [
                'creation_date' => now()->subDays($i + 2)->toDateString(),
            ]));

            SpecificResidenceAgreement::create([
                'contract_id'           => $contract->id,
                'title'                 => "Residencia de prueba #{$i}",
                'internship_initial_date' => now()->toDateString(),
                'task'                  => "Tareas de residencia #{$i}",
                'signing_date'          => null,
                'student_id'            => 1,
                'file'                  => null,
            ]);

            $this->command->info("✅ Convenio Individual Residencia #{$i} creado (Contract ID: {$contract->id})");
        }

        // -------------------------------------------------------
        // 2 Convenios INDIVIDUAL DE PASANTÍA (para Coordinador)
        // -------------------------------------------------------
        for ($i = 1; $i <= 2; $i++) {
            $contract = Contract::create(array_merge($baseData, [
                'creation_date' => now()->subDays($i + 4)->toDateString(),
            ]));

            IndividualInternshipAgreement::create([
                'contract_id'           => $contract->id,
                'months_quantity'       => 6,
                'task'                  => "Tareas de pasantía #{$i}",
                'internship_initial_date' => now()->toDateString(),
                'assignment'            => "Asignación #{$i}",
                'area'                  => "Área de prueba #{$i}",
                'signing_date'          => null,
                'student_id'            => 1,
                'file'                  => null,
            ]);

            $this->command->info("✅ Convenio Individual Pasantía #{$i} creado (Contract ID: {$contract->id})");
        }

        $this->command->info("\n🎉 Seeder completado: 2 Específicos, 2 Individual Residencia, 2 Individual Pasantía.");
    }
}
