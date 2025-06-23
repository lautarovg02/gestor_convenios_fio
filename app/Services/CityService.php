<?php

namespace App\Services;

use App\Models\City;
use App\Services\ProvinceService;

class CityService
{
    protected $provinceService;

    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    public function getOrCreateByNameAndProvince(string $name, string $provinceName): City
    {
        // Buscar o crear la provincia
        $province = $this->provinceService->findOrCreateByName($provinceName);

        // Buscar o crear la ciudad asociada a la provincia (por su id)
        return City::firstOrCreate(
            ['name' => $name, 'province_id' => $province->id],
            ['name' => $name, 'province_id' => $province->id]
        );
    }

    public function findCityById(int $id): ?City
    {
        return City::find($id);
    }
}
