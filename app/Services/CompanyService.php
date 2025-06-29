<?php

namespace App\Services;

use App\Models\Company;
use App\Services\CityService;
use App\Services\CompanyEntityService;
use Illuminate\Support\Collection;

class CompanyService
{
    protected $cityService;
    protected $companyEntityService;

    public function __construct(CityService $cityService, CompanyEntityService $companyEntityService)
    {
        $this->cityService = $cityService;
        $this->companyEntityService = $companyEntityService;
    }

    public function getOrCreateCompany(array $data): Company
    {
        $city = $this->cityService->getOrCreateByNameAndProvince($data['localidad'], $data['provincia']);
        $entity = $this->companyEntityService->getOrCreateByName($data['razon_social']);

        $cuitCompleto = $data['contraparte_cuit_prefijo'] . $data['contraparte_cuit_dni'] . $data['contraparte_cuit_dv'];

        // Buscar primero por company_name
        $existingCompany = Company::where('company_name', $data['razon_social'])->first();

        if ($existingCompany) {
            return $existingCompany;
        }

        // Si no existe, creamos
        return Company::create([
            'denomination' => $data['razon_social'],
            'cuit' => $cuitCompleto,
            'company_name' => $data['razon_social'] ?? null,
            'sector' => $data['contraparte_rubro'] ?? null,
            'company_category' => $data['category'] ?? null,
            'scope' => $data['ambito'] ?? null,
            'street' => $data['calle'] ?? null,
            'number' => $data['nro_calle'] ?? null,
            'city_id' => $city->id,
            'entity_id' => $entity->id,
        ]);
    }

    public function findCompanyById(int $id): ?Company
    {
        return Company::find($id);
    }

    public function findCompanyByCuit(string $cuit): ?Company
    {
        return Company::where('cuit', $cuit)->first();
    }

    public function getCompaniesByTypeFrameworkAgreement(string $type): Collection
    {
        return Company::select('id', 'denomination', 'cuit')->whereHas('contracts', function ($query) use ($type) {
            $query->whereHas('typeFrameworkAgreement', function ($subQuery) use ($type) {
                $subQuery->where('type', $type);
            });
        })->get();
    }
}
