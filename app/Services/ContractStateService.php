<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractStatus;
use Illuminate\Support\Facades\Log;

class ContractStateService
{
    /**
     * Determines and applies the next status for a contract approval.
     *
     * @param Contract $contract
     * @return void
     * @throws \Exception
     */
    public function approve(Contract $contract)
    {
        $nextStatusName = $this->getNextStatusName($contract);

        if (!$nextStatusName) {
            throw new \Exception("No se pudo determinar el siguiente estado para el contrato.");
        }

        $nextStatus = ContractStatus::where('status', $nextStatusName)->first();

        if (!$nextStatus) {
            throw new \Exception("El estado '{$nextStatusName}' no existe en la base de datos.");
        }

        $contract->contract_status_id = $nextStatus->id;
        $contract->save();
    }

    private function getNextStatusName(Contract $contract)
    {
        // 1. Check if it's a Framework Agreement (Convenio Marco)
        // Adjust logic based on your specific TypeFrameworkAgreement IDs or Names
        // Assuming types contain "Marco"
        $isFramework = false;
        if ($contract->typeFrameworkAgreement) {
             if (stripos($contract->typeFrameworkAgreement->type, 'Marco') !== false) {
                 $isFramework = true;
             }
        }

        if ($isFramework) {
            // Logic for Framework Agreement
            return 'SEVyT (Estado de aprobación/Análisis)';
        } else {
            // Logic for Specific Agreement (Convenio Particular)
            // TODO: Refine logic to choose between 'En Departamento' and 'En Coordinación'
            // For now, defaulting to 'En Departamento'
            return 'En Departamento';
        }
    }
}
