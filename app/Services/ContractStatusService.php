<?php

namespace App\Services;

use App\Models\ContractStatus;

class ContractStatusService
{
    /**
     * Busca un ContractStatus por su status y si no existe, lo crea.
     *
     * @param array $data Datos para crear o buscar el ContractStatus. Debe incluir 'status'.
     * @return ContractStatus
     */
    public function createStatus(array $data): ContractStatus
    {

        return ContractStatus::create(
            [
                'status' => $data['status'],
                'time_limit' => $data['time_limit'], // tiempo límite por defecto, podés cambiarlo
            ]);
    }
}
