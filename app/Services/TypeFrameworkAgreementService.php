<?php

namespace App\Services;

use App\Models\TypeFrameworkAgreement;

class TypeFrameworkAgreementService
{
    /**
     * Busca un registro por tipo, si no existe lo crea.
     *
     * @param string $type
     * @return TypeFrameworkAgreement
     */
    public function findOrCreateByType(string $type): TypeFrameworkAgreement
    {
        return TypeFrameworkAgreement::firstOrCreate(
            ['type' => $type],
            ['type' => $type]);
    }
}