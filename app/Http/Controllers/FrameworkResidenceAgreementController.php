<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFrameworkResidenceAgreement;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Province;
use App\Services\CityService;
use App\Services\CompanyService;
use App\Services\ContractStatusService;
use App\Services\EmployeeService;
use App\Services\SecretaryService;
use App\Services\TeacherService;
use App\Services\TypeFrameworkAgreementService;
use App\Services\ProvinceService;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Type;
use Illuminate\Console\View\Components\Secret;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Storage;
use Str;
use Http;
use App\Services\ContractService;

class FrameworkResidenceAgreementController extends Controller
{
    protected $companyService;
    protected $secretaryService;
    protected $teacherService;
    protected $employeeService;
    protected $contractStatusService;
    protected $typeFrameworkAgreementService;
    protected $cityService;
    protected $provinceService;
    protected $contractService;

    public function __construct(
        CityService $cityService,
        CompanyService $companyService,
        SecretaryService $secretaryService,
        TeacherService $teacherService,
        EmployeeService $employeeService,
        ContractStatusService $contractStatusService,
        TypeFrameworkAgreementService $typeFrameworkAgreementService,
        ProvinceService $provinceService,
        ContractService $contractService
    ) {
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
        $this->companyService = $companyService;
        $this->secretaryService = $secretaryService;
        $this->teacherService = $teacherService;
        $this->employeeService = $employeeService;
        $this->contractStatusService = $contractStatusService;
        $this->typeFrameworkAgreementService = $typeFrameworkAgreementService;
        $this->contractService = $contractService;
    }

    public function create()
    {
        $companies   = $this->companyService->getAllCompanies();
        $teachers    = $this->teacherService->getAllTeachers();
        $rectors     = $this->teacherService->getAllRectors();
        $secretaries = $this->secretaryService->getAllSecretaries();

        return view('frameworkResidenceAgreement.create', compact('companies', 'teachers', 'rectors', 'secretaries'));
    }
    

    public function store(StoreFrameworkResidenceAgreement $request)
    {
        $validated = $request->validated();

        // Obtener o crear la empresa (company_id ya validado y sin duplicado por FormRequest)
        $company = $this->companyService->getOrCreateCompany($validated);

        // Obtener empleado de contacto. Si no existe por DNI, crearlo con los datos del form.
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name'         => $validated['contact_nombre'],
            'lastname'     => $validated['contact_apellido'],
            'dni'          => $validated['contact_dni'],
            'cuil'         => ($validated['cuil_prefijo'] ?? '') . ($validated['cuil_dni'] ?? '') . ($validated['cuil_dv'] ?? ''),
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

        // Tipo de convenio
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco de Residencia');

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
        $companyProvince = $companyCity ? $this->provinceService->getById($companyCity->province_id) : null;
        $companyRepresentativeEmployee = $this->companyService->findCompanyById($representative_employee->company_id);

        $templatePath = storage_path('app/plantillas/Convenio_Marco_de_Residencia.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $safe = fn($v) => $v ?? '______';

        $templateProcessor->setValue('razon_social', $company->denomination);
        $templateProcessor->setValue('calle', $company->street ?? '');
        $templateProcessor->setValue('nro_calle', $company->number ?? '');
        $templateProcessor->setValue('ciudad', $companyCity ? $companyCity->name : '');
        $templateProcessor->setValue('provincia', $companyProvince ? $companyProvince->name : '');
        $templateProcessor->setValue('cuit_empresa', $company->cuit ?? '');
        $templateProcessor->setValue('cuil', $safe($validated['cuil_prefijo']) . '-' . $safe($validated['cuil_dni']) . '-' . $safe($validated['cuil_dv']));
        $templateProcessor->setValue('nombre_rep_contacto', $contact_employee->name . ' ' . $contact_employee->lastname);
        $templateProcessor->setValue('cargo_rep_contacto', $contact_employee->position);
        $templateProcessor->setValue('dni_rep_contacto', $contact_employee->dni);
        $templateProcessor->setValue('nombre_rep_firma', $representative_employee->name . ' ' . $representative_employee->lastname);
        $templateProcessor->setValue('cargo_rep_firma', $representative_employee->position);
        $templateProcessor->setValue('rep_firma_empresa_razon_social', $companyRepresentativeEmployee ? $companyRepresentativeEmployee->denomination : '');
        $templateProcessor->setValue('dia', !empty($validated['fecha_firma']) ? Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $templateProcessor->setValue('mes', !empty($validated['fecha_firma']) ? Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');
        $templateProcessor->setValue('anio', !empty($validated['fecha_firma']) ? Carbon::parse($validated['fecha_firma'])->format('Y') : '____');

        $relativePath = 'convenios_generados/' . date('Y/m');
        Storage::makeDirectory($relativePath);

        $nombreArchivo = 'convenio_marco_residencia_' . Str::slug($validated['razon_social']) . '.docx';
        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);
        $templateProcessor->saveAs($fullPath);

        // Guardar la ruta del archivo generado en la DB
        $agreement->update(['file' => $relativePath . '/' . $nombreArchivo]);

        return view('frameworkResidenceAgreement.creationSuccessful', compact('agreement', 'relativePath', 'nombreArchivo'));
    }




    public function download($id)
    {
        $agreement = Contract::findOrFail($id);

        if (!$agreement->file) {
            return redirect()->back()->with('error', 'El archivo no está registrado en el sistema.');
        }

        if (!Storage::exists($agreement->file)) {
            return redirect()->back()->with('error', 'El archivo no se encuentra físicamente en el servidor.');
        }

        return Storage::download($agreement->file, basename($agreement->file));
    }


    public function getCompaniesByFrameworkResidenceAgreement()
    {
        try {
            $companies = $this->companyService->getCompaniesByTypeFrameworkAgreement('Convenio Marco de Residencia');
            return response()->json($companies);
        } catch (\Exception $e) {
            \Log::error('Error al obtener empresas que tengan un convenio marco de residencia' . $e->getMessage());
            return response()->json(['error' => 'Error en el servidor'], 500);
        }
    }

public function searchAgreementByCompany($companyId)
{
    try {
        $contract = Contract::with([
            'company',
            'secretary',
            'teacher',
            'contactEmployee',
            'representativeEmployee',
            'typeFrameworkAgreement'
        ])
        ->where('company_id', $companyId)
        ->whereHas('typeFrameworkAgreement', function ($query) {
            $query->where('type', 'Convenio Marco de Residencia');
        })
        ->first();

        if ($contract) {
            return response()->json([
                'contract_id' => $contract->id,
                'signing_date' => $contract->signing_date,
                'company' => $contract->company,
                'secretary' => $contract->secretary,
                'teacher' => $contract->teacher,
                'contact_employee' => $contract->contactEmployee,
                'representative_employee' => $contract->representativeEmployee,
                'type_framework_agreement' => $contract->typeFrameworkAgreement,
            ]);
        } else {
            return response()->json(['message' => 'Contrato no encontrado'], 404);
        }
    } catch (\Exception $e) {
        \Log::error('Error en searchAgreementByCompany: ' . $e->getMessage());
        return response()->json(['error' => 'Error en el servidor'], 500);
    }
}
}
