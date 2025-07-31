<?php

namespace App\Services;

use App\Models\Contract;

class ContractService
{
    public function getFrameworkAgreements()
    {
        return Contract::with(['company', 'contactEmployee', 'representativeEmployee'])
            ->where('type_framework_agreement_id', 1)
            ->get();
    }
}