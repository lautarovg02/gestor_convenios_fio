<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Contract;

use Illuminate\Http\Request;

class ContractController extends Controller
{ /**
     * Muestra una lista de todos los contratos de una empresa.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\View\View
     */
    public function showContracts(Company $company)
    {
        // Esto accede a la relación 'contracts'
        $contracts = $company->contracts; 
        
        // Retorna la vista y le pasa los contratos y la empresa
       return view('agreements.contractCompany', compact('company', 'contracts'));
    }
    
}
