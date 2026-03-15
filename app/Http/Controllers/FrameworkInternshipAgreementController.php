<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFrameworkInternshipAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Contract;
use App\Services\CityService;
use App\Services\CompanyService;
use App\Services\ContractService;
use App\Services\SecretaryService;
use App\Services\TeacherService;
use App\Services\EmployeeService;
use App\Services\ContractStatusService;
use App\Services\TypeFrameworkAgreementService;

class FrameworkInternshipAgreementController extends Controller
{

    protected $companyService;
    protected $secretaryService;
    protected $teacherService;
    protected $employeeService;
    protected $contractStatusService;
    protected $typeFrameworkAgreementService;
    protected $cityService;
    protected $contractService;

    public function __construct(

        ContractService $contractService,
        CityService $cityService,
        CompanyService $companyService,
        SecretaryService $secretaryService,
        TeacherService $teacherService,
        EmployeeService $employeeService,
        ContractStatusService $contractStatusService,
        TypeFrameworkAgreementService $typeFrameworkAgreementService
    ) {
        $this->contractService = $contractService;
        $this->typeFrameworkAgreementService = $typeFrameworkAgreementService;
        $this->contractStatusService = $contractStatusService;
        $this->employeeService = $employeeService;
        $this->teacherService = $teacherService;
        $this->secretaryService = $secretaryService;
        $this->companyService = $companyService;
        $this->cityService = $cityService;
    }




    /*
       public function index()
    {
        return view('frameworkInternshipAgreement.index'); 
    }
*/

    public function create()
    {
        $companies  = $this->companyService->getAllCompanies();
        $teachers   = $this->teacherService->getAllTeachers();
        $rectors    = $this->teacherService->getAllRectors();
        $secretaries = $this->secretaryService->getAllSecretaries();
        return view("frameworkInternshipAgreement.create", compact('companies', 'teachers', 'rectors', 'secretaries'));
    }



    public function store(StoreFrameworkInternshipAgreement $request)
    {
        $validated = $request->validated();

        // Obtener o crear la empresa (basado en company_id ya validado)
        $company = $this->companyService->getOrCreateCompany($validated);

        // Obtener empleado de contacto. Si no existe por DNI, crearlo con los datos del form.
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name'         => $validated['contact_nombre'],
            'lastname'     => $validated['contact_apellido'],
            'dni'          => $validated['contact_dni'],
            'cuil'         => $validated['contact_cuil'] ?? null,
            'email'        => $validated['contact_email'],
            'phone'        => $validated['contact_celular'],
            'position'     => $validated['contact_cargo'],
            'is_represent' => true,
            'company_id'   => $company->id,
        ]);

        // Obtener representante firmante. Si no existe por DNI, crearlo.
        $representative_employee = $this->employeeService->findOrCreateByDni([
            'name'         => $validated['firma_nombre'],
            'lastname'     => $validated['firma_apellido'],
            'dni'          => $validated['firma_dni'],
            'cuil'         => null,
            'email'        => $validated['firma_email'],
            'phone'        => $validated['firma_celular'] ?? null,
            'position'     => $validated['firma_cargo'],
            'is_represent' => true,
            'company_id'   => $company->id,
        ]);

        // Obtener docente, rector y secretaria desde IDs seleccionados en el formulario
        $teacher   = $this->teacherService->getAllTeachers()->find($validated['teacher_id']);
        $rector    = $this->teacherService->getAllRectors()->find($validated['rector_id']);
        $secretary = $this->secretaryService->getAllSecretaries()->find($validated['secretary_id']);

        if (!$teacher || !$rector || !$secretary) {
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'Docente, rector o secretaria no encontrados. Por favor verificá los datos del formulario.']);
        }

        // Estado inicial del convenio marco
        $contract_status = \App\Models\ContractStatus::firstOrCreate(['status' => 'SEVyT']);

        // Tipo de convenio (obtener por ID deterministico = 1 para Pasantia)
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco de Pasantía');

        // Guardar documentos adjuntos
        $adjuntosPath = 'documentacion/adjuntos/' . date('Y/m');
        Storage::makeDirectory($adjuntosPath);
        $urlAfip        = null;
        $urlEstatuto    = null;
        $urlAutoridades = null;
        if ($request->hasFile('doc_afip')) {
            $f = $request->file('doc_afip');
            $name = 'afip-' . time() . '.' . $f->getClientOriginalExtension();
            $f->storeAs($adjuntosPath, $name);
            $urlAfip = $adjuntosPath . '/' . $name;
        }
        if ($request->hasFile('doc_estatuto')) {
            $f = $request->file('doc_estatuto');
            $name = 'estatuto-' . time() . '.' . $f->getClientOriginalExtension();
            $f->storeAs($adjuntosPath, $name);
            $urlEstatuto = $adjuntosPath . '/' . $name;
        }
        if ($request->hasFile('doc_autoridades')) {
            $f = $request->file('doc_autoridades');
            $name = 'autoridades-' . time() . '.' . $f->getClientOriginalExtension();
            $f->storeAs($adjuntosPath, $name);
            $urlAutoridades = $adjuntosPath . '/' . $name;
        }

        // Crear el contrato
        $agreement = Contract::create([
            'signing_date'                => Carbon::parse($validated['fecha_firma']),
            'url_certificate_afip'        => $urlAfip,
            'url_statute'                 => $urlEstatuto,
            'url_assignment_authorities'  => $urlAutoridades,
            'company_id'                  => $company->id,
            'secretary_id'                => $secretary->id,
            'teacher_id'                  => $teacher->id,
            'contact_employee_id'         => $contact_employee->id,
            'representative_employee_id'  => $representative_employee->id,
            'rector'                      => $rector->id,
            'contract_status_id'          => $contract_status->id,
            'type_framework_agreement_id' => $type->id,
            'file'                        => null,
            'creation_date'               => Carbon::now(),
        ]);

        // Generación del documento Word
        $companyCity = $this->cityService->findCityById($company->city_id);
        $companyRepresentativeEmployee = $this->companyService->findCompanyById($representative_employee->company_id);

        $templatePath = storage_path('app/plantillas/Convenio_Marco_de_Pasantia.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('razon_social', $company->denomination);
        $templateProcessor->setValue('calle', $company->street);
        $templateProcessor->setValue('nro_calle', $company->number);
        $templateProcessor->setValue('ciudad', $companyCity ? $companyCity->name : '');
        $templateProcessor->setValue('nombre_rep_firma', $representative_employee->name . ' ' . $representative_employee->lastname);
        $templateProcessor->setValue('cargo_rep_firma', $representative_employee->position);
        
        $templateProcessor->setValue('dia', !empty($validated['fecha_firma']) ? Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $templateProcessor->setValue('mes', !empty($validated['fecha_firma']) ? Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');
        $templateProcessor->setValue('anio', !empty($validated['fecha_firma']) ? Carbon::parse($validated['fecha_firma'])->format('Y') : '____');
        
        $relativePath = 'convenios_generados/' . date('Y/m');
        Storage::makeDirectory($relativePath);

        $nombreArchivo = 'convenio_marco_pasantias_' . Str::slug($validated['razon_social']) . '.docx';
        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);
        $templateProcessor->saveAs($fullPath);

        return view('frameworkInternshipAgreement.creationSuccessful', compact('relativePath', 'nombreArchivo'));
    }
    
public function download(Request $request)
{
    $path = $request->get('path');
    $file = $request->get('file');

    if (!$path || !$file) {
        abort(400, 'Parámetros inválidos');
    }

    $fullPath = storage_path('app/' . ltrim($path, '/') . '/' . $file);

    if (!file_exists($fullPath)) {
        abort(404, 'Archivo no encontrado');
    }

    return response()->download($fullPath);
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
