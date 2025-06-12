<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Database\Eloquent\Collection;

class ProvinceService
{
    /**
     * Buscar una provincia por nombre, o crearla si no existe.
     */
    public function findOrCreateByName(string $name): Province
    {
        return Province::firstOrCreate(
            ['name' => $name],
            ['name' => $name]);
    }
}
