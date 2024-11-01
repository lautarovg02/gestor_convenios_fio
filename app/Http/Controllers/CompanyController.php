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
    public function index(Request $request)
    {
        $searchTerm = $request->input('search'); // Obtiene el termino de búsqueda
        $companies = collect(); // Inicializa una colección vacía
        $errorMessage = null; // Variable para el mensaje de error
        $loadingMessage = null; // Variable para el mensaje de carga

        try {

            // Mensaje que se muestra durante la carga
            $loadingMessage = 'Cargando empresas...';

            // Obtener todas las compañías activas usando el modelo Company y el scope de búsqueda
            $companies = Company::enabled()->search($searchTerm)->paginate(9);

            } catch (\Exception $e) {
                $errorMessage = 'No se pudo recuperar la información de empresas en este momento. Por favor, inténtelo más tarde.';
                // Opcional: Puedes registrar el error para fines de depuración.
                \Log::error('Error al obtener compañías: ' . $e->getMessage());
            }

            return view('companies.index', compact('companies', 'searchTerm', 'errorMessage'));
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
    public function update(StoreCompanyRequest $request, Company $company) : RedirectResponse
    {
        try{
            dd($request);
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
