<?php

namespace App\Http\Controllers;

use App\Models\Company as CompanyModel; 
use Illuminate\Http\Request;

class Company extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $searchTerm = $request->input('search');

        // Obtener todas las compañías usando el modelo Company y el scope de búsqueda
        $companies = CompanyModel::search($searchTerm)->paginate(9);

        return view('companies.index', compact('companies', 'searchTerm'));
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
