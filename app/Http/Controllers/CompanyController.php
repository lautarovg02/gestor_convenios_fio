<?php

namespace App\Http\Controllers;

use App\Enums\EntityType;
use App\Http\Requests\StoreCompanyRequest;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyEntity;
use App\Models\Contract;
use App\Models\Employee;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\CompanyEntityService;
use App\Services\CityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 
use App\Http\Requests\UpdateCompanyRequest;


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
        $filters = $request->only(['city', 'scope', 'sector']); //Varialbes para filtrar empresas

        try {

            // Mensaje que se muestra durante la carga
            $loadingMessage = 'Cargando empresas...';

            // Obtener todas las ciudades para el filtro
            $cities = City::orderBy('name', 'ASC')->get();

            // Obtener todas las compañías activas usando el modelo Company y el scope de búsqueda y filtro
            $companies = Company::enabled()->search($searchTerm)->filter($filters)->paginate(10);

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
                ->map(function ($company) {
                    return $company->scope ?: 'N/A';
                })
                ->unique();
        } catch (\Exception $e) {
            $errorMessage = 'No se pudo recuperar la información de empresas en este momento. Por favor, inténtelo más tarde.';
            // Opcional: Puedes registrar el error para fines de depuración.
            \Log::error('Error al obtener las empresas: ' . $e->getMessage());
        }

        // Verifica si no se encontraron empresas después del filtro
        if ($companies->isEmpty()) {
            $errorMessage = 'No se ha encontrado ninguna compañía con los filtros seleccionados.';
        }

        return view('companies.index', compact('companies', 'cities', 'sectors', 'scopes', 'searchTerm', 'errorMessage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $entityTypes = CompanyEntity::orderBy('name', 'ASC')->get();
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
    DB::beginTransaction();

    try {
        $validatedData = $request->validated();
        
        // 1. Manejar el campo booleano de la cláusula de confidencialidad
        $validatedData['has_confidentiality_clause'] = $request->filled('has_confidentiality_clause');
        
        // 2. Preparar el nombre de la carpeta para los archivos
        $companySlug = Str::slug($validatedData['denomination']);
        $basePath = "documentacion/{$companySlug}";

        // 3. Subir y guardar las rutas de los archivos
        $handleFileUpload = function ($fileInputName, $fileNamePrefix) use ($request, $basePath, $companySlug) {
            if ($request->hasFile($fileInputName)) {
                $file = $request->file($fileInputName);
                $originalExtension = $file->getClientOriginalExtension();
                $fileName = "{$fileNamePrefix}_{$companySlug}.{$originalExtension}";
                return $file->storeAs($basePath, $fileName, 'local');
            }
            return null;
        };

        $validatedData['url_certificate_afip'] = $handleFileUpload('afip_certificate', 'certificado_afip');
        $validatedData['url_statute'] = $handleFileUpload('statute_confirmation', 'estatuto');
        $validatedData['url_assignment_authorities'] = $handleFileUpload('authorities_assignment', 'designacion_autoridades');
        $validatedData['url_confidentiality_clause_file'] = $handleFileUpload('confidentiality_clause_file', 'clausula_confidencialidad');

        // 4. Limpiar los datos del formulario antes de guardar en la BD
        unset(
            $validatedData['afip_certificate'],
            $validatedData['statute_confirmation'],
            $validatedData['authorities_assignment'],
            $validatedData['confidentiality_clause_file']
        );
        
        // 5. Crear la empresa
        Company::create($validatedData);

        DB::commit();

        return redirect()->route('companies.index')
            ->with('success', 'Empresa ingresada exitosamente.');

    } catch (Exception $e) {
        DB::rollBack();
        \Log::error('Error al crear la empresa: ' . $e->getMessage());

        return redirect()->back()->withInput()->with('error', 'Error al crear la empresa: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     *
     * @param  Company $company
     * @return \Illuminate\Contracts\View\View;
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
     * @return Illuminate\Contracts\View\View;
     *
     */
    public function edit(Company $company): View
    {
        $company = Company::find($company->id);
        $cities = City::orderBy('name', 'ASC')->get();
        $entityTypes = CompanyEntity::orderBy('name', 'ASC')->get();
        return view('companies.edit', ['company' => $company, 'cities' => $cities, 'entityTypes' => $entityTypes]);
    }

    /**
     * Update the specified resource in storage.
     * @param  Company $company
     * @param StoreCompanyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */



public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
{
    DB::beginTransaction();

    try {
        $validatedData = $request->validated();
        
        // Handle the boolean checkbox field
        $validatedData['has_confidentiality_clause'] = $request->filled('has_confidentiality_clause');
        
        // Prepare the base path for document storage
        $companySlug = Str::slug($company->denomination);
        $basePath = "documentacion/{$companySlug}";

        // Function to handle file uploads and deletions
        $handleFileUpdate = function ($fileInputName, $columnName, $fileNamePrefix) use ($request, $company, $basePath, $companySlug) {
            // Check if a new file was uploaded
            if ($request->hasFile($fileInputName)) {
                $file = $request->file($fileInputName);
                
                // Get the old file path from the database
                $oldFilePath = $company->{$columnName};

                // Delete the old file from storage if it exists
                if ($oldFilePath && Storage::disk('local')->exists($oldFilePath)) {
                    Storage::disk('local')->delete($oldFilePath);
                }
                
                // Store the new file with a consistent name
                $originalExtension = $file->getClientOriginalExtension();
                $fileName = "{$fileNamePrefix}_{$companySlug}.{$originalExtension}";
                
                return $file->storeAs($basePath, $fileName, 'local');
            }
            
            // If no new file, keep the existing path
            return $company->{$columnName};
        };

        // Process each document field
        $validatedData['url_certificate_afip'] = $handleFileUpdate('afip_certificate', 'url_certificate_afip', 'certificado_afip');
        $validatedData['url_statute'] = $handleFileUpdate('statute_confirmation', 'url_statute', 'estatuto');
        $validatedData['url_assignment_authorities'] = $handleFileUpdate('authorities_assignment', 'url_assignment_authorities', 'designacion_autoridades');
        $validatedData['url_confidentiality_clause_file'] = $handleFileUpdate('confidentiality_clause_file', 'url_confidentiality_clause_file', 'clausula_confidencialidad');

        // Remove the temporary file keys from the validated data array
        unset(
            $validatedData['afip_certificate'],
            $validatedData['statute_confirmation'],
            $validatedData['authorities_assignment'],
            $validatedData['confidentiality_clause_file']
        );
        
        // Update the company record with the new data
        $company->update($validatedData);

        DB::commit();

        return redirect()->route('companies.show', $company)
            ->with('success', 'Empresa actualizada exitosamente.');

    } catch (Exception $e) {
        DB::rollBack();
        \Log::error('Error al actualizar la empresa: ' . $e->getMessage());

        return redirect()->back()->withInput()->with('error', 'Error al actualizar la empresa: ' . $e->getMessage());
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Encuentra la empresa por ID
        $company = Company::findOrFail($company->id);

        $hasEmployees = Employee::where('company_id', $company->id)->exists();

        //Verifica si hay convenios relacionados a la empresa
        $contractsCount = Contract::where('company_id', $company->id)->count();

        //!!!!NOTA!!!!: Queda para proximo sprint deshabilitar empresa si tiene todos los convenios finalizados, ya que no se eliminan
        if ($contractsCount == 0 && !$hasEmployees) {
            // Si no hay convenios, procede con la eliminación
            $company->delete();

            // Redirecciona a la lista de empresas con un mensaje de éxito
            return redirect()->route('companies.index')->with('success', 'La empresa "' . $company->company_name . '" eliminada exitosamente!');
        }else if($contractsCount >=1){
            //Se redirecciona con mensaje de error.
            return redirect()->route('companies.index')->with('error', 'La empresa "' . $company->company_name . '" tiene convenios activos y no puede ser eliminada.');

        } 
        else if($hasEmployees){   
            return redirect()->route('companies.index')->with('error', 'La empresa "' . $company->company_name . '" tiene empleados asociados y no puede ser eliminada.');
         
        }
       //NOTA!! SI TIENE CONTRACTS Y EMPLEADOS ASOCIADOS VA A ENTRAR EN EL PRIMER ELSE IF Y NO SE PUEDE ELIMINAR
    }

    /**
     * Get company by ID for AJAX requests.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyById(int $id) {
        $company = Company::find($id);

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        //busco el name de la entity relacionada a company
        $eName =  $company->entity_id = CompanyEntity::find($company->entity_id);

    
        return response()->json([
            'id' => $company->id,
            'denomination' => $company->denomination,
            'cuit' => $company->cuit,
            'company_name' => $company->company_name,
            'company_category' => $company->company_category,
            'sector' => $company->sector,
            'scope' => $company->scope,
            'street' => $company->street,
            'number' => $company->number,
            'city' => $company->city->name,
            'provincia' => $company->city->province->name,
            'entity_id' => $company->entity_id,
            'city_id' => $company->city_id,
            'city_name'        => $company->city?->name,
            'entity_name' => $company->entity_id->name ?? null,

        ]);
    }
 public function showContracts(Company $company)
    {
        // Esto accede a la relación 'contracts'
        $contracts = $company->contracts; 
        
        // Retorna la vista y le pasa los contratos y la empresa
       return view('companies.contractCompany', compact('company', 'contracts'));
    }

public function downloadDocument(string $slug, string $documentType)
{
    // 1. Encontrar la empresa por su 'slug'
    $company = Company::where('slug', $slug)->firstOrFail();

    // 2. Determinar la columna de la base de datos según el tipo de documento
    $columnName = 'url_' . $documentType;
    $filePath = $company->{$columnName} ?? null;

    // 3. Verificar si el archivo existe
    if (!$filePath || !Storage::disk('local')->exists($filePath)) {
        return redirect()->back()->with('error', 'El archivo solicitado no está disponible.');
    }

    // 4. Determinar el nombre del archivo para la descarga
    $fileName = Str::slug($company->denomination) . '_' . $documentType . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
    
    // 5. Devolver el archivo para su descarga
    return Storage::disk('local')->download($filePath, $fileName);
}








}
