<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvenioMarcoRequest;
use Illuminate\Http\Request;

class FrameworkAgreementController extends Controller
{
       public function index()
    {
        return view("frameworkAgreement.create"); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $type = $request->query('type'); // lee ?type=marco

        return view("frameworkAgreement.create_$type"); // ej: agreements.create_marco
    
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{

        $validated = app(ConvenioMarcoRequest::class)->validated();
        // guardar usando $validated...


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
