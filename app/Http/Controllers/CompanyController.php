<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
* Class CompanyController
* @package App\Http\Controllers
*/
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index():View
    {
        $companies = Company::orderBy('denomination' , 'ASC')
            ->paginate(12);
        $cities = City::all();

        return view('company.index' , ['companies' => $companies , 'cities' => $cities])
            ->with('i', (request()->input('page', 1) - 1) * $companies->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request):RedirectResponse
    {
        $validated = $request->validate(Company::$rules);

        Company::create($validated);

        return  redirect()->route('companies.index')
            ->with('success' , 'Empresa ingresada exitosamente.');
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
    public function edit(Company $company)
    {
        //dd($company);
        $company = Company::find($company->id);
        $cities = City::orderBy('name' , 'ASC')->get();

        return view('company.edit' , ['company' => $company , 'cities' => $cities]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        request()->validate(Company::$rules);

        $company->update($request->all());

        return redirect()->route('companies.index')->with('success' , 'Empresa fue actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
