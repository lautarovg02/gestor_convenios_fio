<?php

namespace App\Services;

use App\Models\Contract;

class ContractService
{
    protected $typeFrameworkAgreementService;

    public function __construct(TypeFrameworkAgreementService $typeFrameworkAgreementService)
    {
        $this->typeFrameworkAgreementService = $typeFrameworkAgreementService;
    }

    public function getFrameworkAgreements()
    {
        return Contract::with(['company', 'contactEmployee', 'representativeEmployee'])
            ->where('type_framework_agreement_id', 1)   //es uno porque es el tipo de convenio marco
            ->get();
    }

        public function getFrameworkAgreementsByCompany($idCompany, $type_framework_agreement)
    {

        $typeId = $this->typeFrameworkAgreementService->getIdByType($type_framework_agreement);

        return Contract::with(['company', 'contactEmployee', 'representativeEmployee'])
            ->where('company_id', $idCompany)
            ->where('type_framework_agreement_id', $typeId)
            ->get();
    }
}