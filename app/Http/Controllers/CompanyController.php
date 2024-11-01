<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Company;
use Exception;
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
            ->paginate(10);

        $cities = City::orderBy('name', 'ASC')->get();

        return view('companies.index' , ['companies' => $companies , 'cities' => $cities])
            ->with('i', (request()->input('page', 1) - 1) * $companies->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create():View
    {
        $cities = City::orderBy('name', 'ASC')->get();
        return view('companies.create' , compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request):RedirectResponse
    {
        try{
            $exists = Company::where('cuit',$request->cuit)->first();

            if($exists) throw new Exception("Cuit duplicado");

            Company::create($request->validated());

            return  redirect()->route('companies.index')
                ->with('success' , 'Empresa ingresada exitosamente.');
        }
        catch(Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company):View
    {
        $company = Company::find($company->id);

        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company):View
    {
        //dd($company);
        $company = Company::find($company->id);
        $cities = City::orderBy('name' , 'ASC')->get();

        return view('companies.edit' , ['company' => $company , 'cities' => $cities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCompanyRequest $request, Company $company):RedirectResponse
    {
        try{

            $exists  = Company::where('cuit', $request->cuit)
            ->where('id', '<>', $company->id)
            ->first();

            if($exists) throw new Exception("Cuit duplicado");

            $company->update($request->validated());
            return redirect()->route('companies.index')->with('success' , 'Empresa actualizada exitosamente.');
        }
        catch(Exception $e)
        {
            return redirect()->route('companies.edit', $company->id)->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * SE DEBE ADVERTIR SOBRE LA IMPOSIBILIDAD DE HACERLO Y DAR OPCIÓN DE DESVINCULAR
     * PARA NO MOSTRARLA EN EL LISTADO
     */
    public function destroy(Company $company)
    {
        //
    }

}
