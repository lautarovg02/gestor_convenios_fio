<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvenioMarcoRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Contract;
use App\Services\CityService;
use App\Services\CompanyService;
use App\Services\ContractService;
use App\Services\SecretaryService;
use App\Services\TeacherService;
use App\Services\EmployeeService;
use App\Services\ContractStatusService;
use App\Services\TypeFrameworkAgreementService;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrameworkAgreementController extends Controller
{
    protected $contractService;
    protected $cityService;
    protected $companyService;
    protected $secretaryService;
    protected $teacherService;
    protected $employeeService;
    protected $contractStatusService;
    protected $typeFrameworkAgreementService;

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
        $this->cityService = $cityService;
        $this->companyService = $companyService;
        $this->secretaryService = $secretaryService;
        $this->teacherService = $teacherService;
        $this->employeeService = $employeeService;
        $this->contractStatusService = $contractStatusService;
        $this->typeFrameworkAgreementService = $typeFrameworkAgreementService;
    }

    public function index()
    {
        return view('frameworkAgreement.index');
    }

    public function create()
    {
        // Obtener datos para los selects (solo empresas que no tienen convenio marco común)
        $companies = $this->companyService->getCompaniesWithoutTypeFrameworkAgreement('Convenio Marco');
        
        // Asumiendo que TeacherService tiene estos métodos (o usas Eloquent directo si no)
        $teachers = $this->teacherService->getAllTeachers(); 
        $rectors = $this->teacherService->getAllRectors(); 
        $secretaries = $this->secretaryService->getAllSecretaries(); 

        return view('frameworkAgreement.create', compact('companies', 'teachers', 'secretaries', 'rectors'));
    }

    public function store(ConvenioMarcoRequest $request)
    {
        $validated = $request->validated();

        // 1) Tipo de convenio marco
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco');

        // 2) Obtener o crear empresa
        $company = $this->companyService->getOrCreateCompany($validated);

        // 3) Validar que no exista convenio previo
        $existsContract = $this->contractService->getFrameworkAgreementsByCompany($company->id, "Convenio Marco");
        if ($existsContract->isNotEmpty()) {
            return redirect()->back()->withErrors([
                'error' => 'Ya existe un Convenio Marco para la empresa ' . $validated['razon_social']
            ])->withInput();
        }

        // 4) Empleado de contacto (Contraparte)
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name'         => $validated['contact_nombre'],
            'lastname'     => $validated['contact_apellido'],
            'dni'          => $validated['contact_dni'],
            'cuil'         => $validated['contact_cuil'] ?? (($validated['cuil_prefijo'] ?? '') . ($validated['cuil_dni'] ?? '') . ($validated['cuil_dv'] ?? '')),
            'email'        => $validated['contact_email'],
            'phone'        => $validated['contact_celular'] ?? null,
            'position'     => $validated['contact_cargo'] ?? null,
            'is_represent' => true,
            'company_id'   => $company->id,
        ]);

        // 5) Representante de firma (Contraparte)
        $representative_employee = $this->employeeService->findOrCreateByDni([
            'name'         => $validated['firma_nombre'],
            'lastname'     => $validated['firma_apellido'],
            'dni'          => $validated['firma_dni'],
            'cuil'         => null,
            'email'        => $validated['firma_email'] ?? null,
            'phone'        => $validated['firma_celular'] ?? null,
            'position'     => $validated['firma_cargo'] ?? null,
            'is_represent' => true,
            'company_id'   => $company->id,
        ]);

        // 6) Estado inicial del contrato
        $contract_status = \App\Models\ContractStatus::firstOrCreate(['status' => 'SEVyT']);
        // Si tu servicio devuelve el modelo, usa $contract_status->id abajo. Si devuelve ID, úsalo directo.
        // Asumiré que devuelve el Modelo para el ejemplo, si no ajusta a $contract_status

        // 7) GUARDAR LOS ADJUNTOS
        $fileFields = [
            'doc_afip'        => 'url_certificate_afip',
            'doc_estatuto'    => 'url_statute',
            'doc_autoridades' => 'url_assignment_authorities',
        ];

        $adjuntosRelativePath = 'documentacion/adjuntos/' . date('Y/m');
        Storage::makeDirectory($adjuntosRelativePath);

        foreach ($fileFields as $inputName => $dbField) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $fileName = Str::slug($dbField) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($adjuntosRelativePath, $fileName);
                $validated[$dbField] = $adjuntosRelativePath . '/' . $fileName;
            }
        }

        // 8) Crear contrato (AQUÍ REEMPLAZAMOS LOS DATOS RANDOM POR LOS DEL REQUEST)
        $agreement = Contract::create([
            'signing_date'               => Carbon::parse($validated['fecha_firma']),
            'url_certificate_afip'       => $validated['url_certificate_afip'] ?? null,
            'url_statute'                => $validated['url_statute'] ?? null,
            'url_assignment_authorities' => $validated['url_assignment_authorities'] ?? null,
            'company_id'                 => $company->id,
            
            // --- DATOS SELECCIONADOS EN LA VISTA ---
            'secretary_id'               => $validated['secretary_id'], // Select de Secretaria
            'teacher_id'                 => $validated['teacher_id'],   // Select de Profesor
            'rector'                     => $validated['rector_id'],    // Select de Rector
            
            'creation_date'              => Carbon::parse($validated['fecha_firma']),
            'contact_employee_id'        => $contact_employee->id,
            'representative_employee_id' => $representative_employee->id,
            
            'contract_status_id'         => 1, // 1 = SEVyT
            'type_framework_agreement_id'=> $type->id ?? 1,
            'file'                       => null 
        ]);

        // ------------------ GENERACIÓN DE DOCUMENTO ------------------

        function safe($value) {
            return $value ?? '______';
        }

        $template = new TemplateProcessor(storage_path('app/plantillas/convenio_marco.docx'));

        // Mapeo de datos de la empresa y contraparte
        $template->setValue('razon_social', $validated['razon_social'] ?? '________');
        $template->setValue('calle', $validated['calle'] ?? '________');
        $template->setValue('nro_calle', $validated['nro_calle'] ?? '________');
        $template->setValue('ciudad', $validated['localidad'] ?? '________');
        $template->setValue('provincia', safe($validated['provincia']));
        $template->setValue('cuil', safe($validated['cuil_prefijo']) . '-' . safe($validated['cuil_dni']) . '-' . safe($validated['cuil_dv']));
        $template->setValue('cuit', safe($validated['cuit_prefijo']) . '-' . safe($validated['cuit_dni']) . '-' . safe($validated['cuit_dv']));
        $template->setValue('rubro', safe($validated['contraparte_rubro'] ?? null));
        $template->setValue('dedicacion', safe($validated['dedicacion'] ?? null));
        $template->setValue('entidad', safe($validated['entidad']));
        
        $template->setValue('nombre_rep_contacto', safe($validated['contact_nombre']) . ' ' . safe($validated['contact_apellido']));
        $template->setValue('cargo_rep_contacto', safe($validated['contact_cargo']));
        
        $template->setValue('nombre_rep_firma', safe($validated['firma_nombre']) . ' ' . safe($validated['firma_apellido']));
        $template->setValue('cargo_rep_firma', safe($validated['firma_cargo']));
        $template->setValue('firma_dni', safe($validated['firma_dni']));
        $template->setValue('rep_firma_empresa_razon_social', safe($validated['firma_empresa_razon_social']));
        
        $template->setValue('dia', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $template->setValue('mes', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');
        $template->setValue('anio', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('Y') : '____');

        // Nota: Si necesitas mapear el nombre del Rector/Profesor en el Word, 
        // deberías buscar los modelos usando los IDs ($validated['rector_id']) aquí.

        $relativePath = 'convenios_generados/convenios_marcos/' . date('Y/m');
        Storage::makeDirectory($relativePath);

        $nombreArchivo = 'convenio_marco_' . Str::slug($validated['razon_social'] ?? 'sin-razon-social') . '.docx';
        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);
        
        $template->saveAs($fullPath);

        // Guardar la ruta del archivo generado en la DB
        $agreement->update(['file' => $relativePath . '/' . $nombreArchivo]);

        return view('frameworkAgreement.creationSuccessful', compact('agreement', 'relativePath', 'nombreArchivo'));
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

    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) { return back()->with('info', 'Pendiente'); }
    public function destroy(string $id) { return back()->with('info', 'Pendiente'); }
}