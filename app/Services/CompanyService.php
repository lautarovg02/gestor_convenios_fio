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
        // Si se provee un company_id válido, retornamos esa empresa directamente
        if (!empty($data['company_id'])) {
            $company = Company::find($data['company_id']);
            if ($company) {
                return $company;
            }
        }

        $city = $this->cityService->getOrCreateByNameAndProvince($data['localidad'], $data['provincia']);
        $entity = $this->companyEntityService->getOrCreateByName($data['razon_social']);

        // Buscar primero por company_name
        $existingCompany = Company::where('company_name', $data['razon_social'])->first();

        if ($existingCompany) {
            return $existingCompany;
        }

        // Construir el CUIT a partir de partes si no viene entero
        $cuit = $data['contraparte_cuit'] ?? null;
        if (!$cuit && isset($data['cuit_prefijo']) && isset($data['cuit_dni']) && isset($data['cuit_dv'])) {
            $cuit = $data['cuit_prefijo'] . $data['cuit_dni'] . $data['cuit_dv'];
        }

        // Buscar por CUIT si está disponible
        if ($cuit) {
            $existingCompanyByCuit = Company::where('cuit', $cuit)->first();
            if ($existingCompanyByCuit) {
                return $existingCompanyByCuit;
            }
        }

        // Si no existe, creamos
        return Company::create([
            'denomination' => $data['razon_social'],
            'cuit' => $cuit,
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

    public function getCompaniesWithoutTypeFrameworkAgreement(string $type): Collection
    {
        return Company::select('id', 'denomination', 'cuit')->whereDoesntHave('contracts', function ($query) use ($type) {
            $query->whereHas('typeFrameworkAgreement', function ($subQuery) use ($type) {
                $subQuery->where('type', $type);
            })->whereHas('status', function ($statusQuery) {
                $statusQuery->whereNotIn('status', ['Deshabilitado', 'Finalizado']);
            });
        })->get();
    }

    public function getAllCompanies(): Collection
    {
        return Company::select('id', 'denomination', 'cuit')->get();    
    }
}
