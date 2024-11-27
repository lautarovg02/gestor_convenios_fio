<?php

namespace App\Http\Controllers;

use App\Enums\EntityType;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyEntity;
use App\Models\Employee;
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
        $filters = $request->only(['city', 'scope','sector']); //Varialbes para filtrar empresas

        try {

            // Mensaje que se muestra durante la carga
            $loadingMessage = 'Cargando empresas...';

            // Obtener todas las ciudades para el filtro
            $cities = City::orderBy('name' , 'ASC')->get();

            // Obtener todas las compañías activas usando el modelo Company y el scope de búsqueda y filtro
            $companies = Company::enabled()->search($searchTerm)->filter($filters)->paginate(9);

            //Obtener todos los sectors y no solo los 9 que se obtienen de las compañias paginadas
            //Reemplaza en la colección de $sector, los sectores vacíos con N/A antes de enviarlos a la vista. map()
            $sectors = Company::select('sector')->distinct()->orderBy('sector', 'ASC')->get()
                                ->map(function ($company) {
                                    return $company->sector ?: 'N/A';
                                    })
                                ->unique();

            //Obtener todos los scopes y no solo los 9 que se obtienen de las compañias paginadas
            //Reemplaza en la colección de $scopes, los sectores vacíos con N/A antes de enviarlos a la vista. map()
            $scopes = Company::select('scope')->distinct()->orderBy('scope', 'asc')->get()
                                ->map(function($company) {
                                    return $company->scope ? : 'N/A';
                                })
                                ->unique();

        } catch (\Exception $e) {
            $errorMessage = 'No se pudo recuperar la información de empresas en este momento. Por favor, inténtelo más tarde.';
            // Opcional: Puedes registrar el error para fines de depuración.
            \Log::error('Error al obtener compañías: ' . $e->getMessage());
        }

         // Verifica si no se encontraron empresas después del filtro
        if ($companies->isEmpty()) {
            $errorMessage = 'No se ha encontrado ninguna compañía con los filtros seleccionados.';
        }

        return view('companies.index',compact('companies','cities','sectors', 'scopes', 'searchTerm', 'errorMessage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $entityTypes = EntityType::values();
        $cities = City::orderBy('name', 'ASC')->get();

        return view('companies.create', compact('cities', 'entityTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        try {
            // Verificar si el CUIT ya existe
            $exists = Company::where('cuit', $request->cuit)->first();
            if ($exists) throw new Exception("Cuit duplicado");

            //Si se seleccionó other_entity se reemplaza el valor de entity por other_entity
            $entitySelected = $request->entity === 'other' ? $request->other_entity_input : $request->entity;

            //Crear la empresa con el valor seleccionado de entidad
            Company::create(array_merge($request->validated(), ['entity' => $entitySelected]));

            return  redirect()->route('companies.index')
                ->with('success', 'Empresa ingresada exitosamente.');
        } catch (Exception $e) {
            \Log::error('Error al crear la empresa: '.$e->getMessage());

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company): View
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
    public function edit(Company $company): View
    {
        //dd($company);
        $company = Company::find($company->id);
        $cities = City::orderBy('name', 'ASC')->get();
        $entityTypes = CompanyEntity::orderBy('name', 'ASC')->get();
        return view('companies.edit', ['company' => $company, 'cities' => $cities, 'entityTypes' => $entityTypes]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCompanyRequest $request, Company $company): RedirectResponse
    {
        try {
            // Verifica si el CUIT es duplicado
            $exists = Company::where('cuit', $request->cuit)
                ->where('id', '<>', $company->id)
                ->first();

            if ($exists) {
                throw new Exception("Cuit duplicado");
            }

            // Verifica si la entidad es "Otro tipo" y actualiza el campo correspondiente
            if ($request->entity === 'other' && $request->has('other_entity_input') && $request->other_entity_input) {
                $entityEdited = $request->other_entity_input;
            } else {
                $entityEdited  = $request->entity;
            }

            // Actualiza el resto de los campos del modelo utilizando los datos validados
                $company->update(array_merge($request->validated(), ['entity' => $entityEdited]));

            // Redirige con un mensaje de éxito
            return redirect()->route('companies.index')->with('success', 'Empresa actualizada exitosamente.');
        } catch (Exception $e) {
            // Redirige en caso de error
            return redirect()->route('companies.edit', $company->id)->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Encuentra la empresa por ID
        $company = Company::findOrFail($company->id);

        //Verifica si hay empleados relacionados a la empresa
        $employeesCount = Employee::where('company_id', $company->id)->count();

        //!!!!NOTA!!!!: Las condición dentro de los operadores if son temporales hasta que la tabla 'contract' esta disponible.
        if ($employeesCount == 0) {
            // Si no hay empleados, procede con la eliminación
            $company->delete();

            // Redirecciona a la lista de empresas con un mensaje de éxito
            return redirect()->route('companies.index')->with('success', 'La empresa "<span class="fw-bold">' . $company->denomination . '</span>" eliminada exitosamente!');
        } else if ($employeesCount > 3) {   //Si la empresa solo tiene convenios finalizados, se deshabilita.
            $company->is_enabled = false;

            //Se actualiza a la empresa en la base de datos
            $company->save();

            //Se redirecciona con mensaje de success
            return redirect()->route('companies.index')->with('success', 'La empresa "<span class="fw-bold">' . $company->denomination . '</span>" fue deshabilitada correctamente.');
        } else if ($employeesCount <= 3) {   //Si la empresa tiene convenios en curso, no se puede ni eliminar ni deshabilitar.
            //Se redirecciona con mensaje de error
            return redirect()->route('companies.index')->with('error', 'La empresa "<span class="fw-bold">' . $company->denomination . '</span>" tiene convenios activos y no puede ser deshabilitada.');
        }
    }
}
