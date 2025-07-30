<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $agreements = Contract::where('contract_status_id','!=',10)->orderBy('creation_date', 'desc')->paginate(10);
            $typeFrameworkAgreement = TypeFrameworkAgreement::orderBy('type', 'ASC')->get();
            $statuses = ContractStatus::where('id','!=',10)->orderBy('status', 'ASC')->get();
            if ($agreements->isEmpty()) {
                return view('agreements.index')->with(['agreements' => $agreements, 'noResults' => true]);
            }
            return view('agreements.index', compact('agreements', 'typeFrameworkAgreement','statuses'));
        }catch(\Exception $e) {
            return redirect()->route('agreements.index')->with(['error' => 'Error al cargar los acuerdos. Inténtalo nuevamente.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
        //
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
