<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractStatus;
use Illuminate\Support\Facades\Log;

class ContractStateService
{
    /**
     * Determines and applies the next status for a contract or agreement approval.
     *
     * @param mixed $model
     * @return void
     * @throws \Exception
     */
    public function approve($model)
    {
        // Si no es un Convenio Marco, revisamos el estado del padre (Contract)
        if (!($model instanceof \App\Models\Contract)) {
            $parentStatus = $model->contract->status->status ?? null;
            if (in_array($parentStatus, ['Finalizado', 'Deshabilitado'])) {
                throw new \Exception("No se puede aprobar este convenio hijo porque su Convenio Marco está Finalizado o Deshabilitado.");
            }
        }

        $nextStatusName = $this->getNextStatusName($model);

        if (!$nextStatusName) {
            throw new \Exception("No se pudo determinar el siguiente estado para el convenio o ya se encuentra Finalizado.");
        }

        $nextStatus = ContractStatus::where('status', $nextStatusName)->first();

        if (!$nextStatus) {
            throw new \Exception("El estado '{$nextStatusName}' no existe en la base de datos.");
        }

        $model->contract_status_id = $nextStatus->id;
        $model->save();
    }

    private function getNextStatusName($model)
    {
        $currentStatus = $model->status->status ?? null;
        
        if (!$currentStatus) {
            return null;
        }

        if ($model instanceof \App\Models\Contract) {
            // Flujo de Convenios Marco
            $flow = [
                'SEVyT' => 'SEVyT firma',
                'SEVyT firma' => 'En ejecución',
                'En ejecución' => 'Finalizado'
            ];
            return $flow[$currentStatus] ?? null;
        }

        // Flujo de Convenios Hijos (Specific, Pasantía, Residencia)
        $flow = [
            'En Departamento' => 'SEVyT',
            'En Coordinación' => 'SEVyT',
            'SEVyT' => 'SEVyT firma',
            'SEVyT firma' => 'Contraparte',
            'Contraparte' => 'Enviar a CA',
            'Enviar a CA' => 'En CA',
            'En CA' => 'En ejecución',
            'En ejecución' => 'Finalizado'
        ];

        return $flow[$currentStatus] ?? null;
    }
}
