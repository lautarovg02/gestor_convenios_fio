<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use App\Models\Contract;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        $agreements = Contract::where('company_id', $company->id)->orderBy('creation_date', 'desc')->get();
        $typeFrameworkAgreement = TypeFrameworkAgreement::orderBy('type', 'ASC')->get();
        $statuses = ContractStatus::where('id','!=',10)->orderBy('status', 'ASC')->get();
        $company = Company::findOrFail($company->id);
        
        return view('agreements.index', compact('company', 'agreements','typeFrameworkAgreement', 'statuses'));
        
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
