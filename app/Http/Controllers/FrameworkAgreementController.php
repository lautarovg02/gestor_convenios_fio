<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvenioMarcoRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Contract;
use App\Models\EmployeePhone;
use App\Services\CityService;
use App\Services\CompanyService;
use App\Services\ContractService;
use App\Services\SecretaryService;
use App\Services\TeacherService;
use App\Services\EmployeeService;
use App\Services\ContractStatusService;
use App\Services\TypeFrameworkAgreementService;
use PhpOffice\PhpWord\TemplateProcessor;
use Storage;
use Str;
use Faker\Factory;
use Illuminate\Support\Facades\Http;

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
        // Trae empresas para el select (ajustá si tenés otro método)
        $companies = $this->companyService->getAllCompanies();

        return view('frameworkAgreement.create', compact('companies'));
    }


    public function store(ConvenioMarcoRequest $request)
    {
        $validated = $request->validated();

        // 1) Tipo de convenio marco
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco');

         //obtener empresa
         $company = $this->companyService->getOrCreateCompany($validated);

        // Si viene empresa seleccionada, validamos que no exista otro convenio marco
        $existsContract = $this->contractService->getFrameworkAgreementsByCompany($company->id, "Convenio Marco");
        if ($existsContract->isNotEmpty()) {
            return redirect()->back()->withErrors([
                'error' => 'Ya existe un Convenio Marco para la empresa ' . $validated['razon_social']
            ])->withInput();
        }
        
       


        // 5) Empleado de contacto
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name'        => $validated['contact_nombre'],
            'lastname'    => $validated['contact_apellido'],
            'dni'         => $validated['contact_dni'],
            'cuil'        => $validated['contact_cuil'] ?? (($validated['cuil_prefijo'] ?? '') .($validated['cuil_dni'] ?? '') .($validated['cuil_dv'] ?? '')),
            'email'       => $validated['contact_email'],
            'phone'        => $validated['contact_celular'] ?? null,
            'position'    => $validated['contact_cargo'] ?? null,
            'is_represent' => true,
            'company_id'  => $company->id,
        ]);


        // 6) Representante (firma)
        $representative_employee = $this->employeeService->findOrCreateByDni([
            'name'        => $validated['firma_nombre'],
            'lastname'    => $validated['firma_apellido'],
            'dni'         => $validated['firma_dni'],
            'cuil'        => null,
            'email'       => $validated['firma_email'] ?? null,
            'phone'        => $validated['firma_celular'] ?? null,
            'position'    => $validated['firma_cargo'] ?? null,
            'is_represent' => true,
            'company_id'  => $company->id,
        ]);


        // 8) Estado inicial del contrato
        $contract_status = $this->contractStatusService->createStatus([
            'status'     => 'SEVyT (Estado de aprobación/Análisis)',
            'time_limit' => 48, // horas
        ]);

        //instancia para generar randoms
        $faker = Factory::create();

            $rector = $this->teacherService->findOrCreateByDni([
            'name' => $faker->firstName,
            'lastname' => $faker->lastName,
            'dni' => $faker->unique()->numberBetween(20000000, 40000000),
            'cuil' => '20' . $faker->unique()->numberBetween(20000000, 40000000) . '3',
            'faculty' => $faker->word,
            'is_rector' => true,
            'is_dean' => $faker->boolean,
        ]);

            $teacher = $this->teacherService->findOrCreateByDni([
            'name' => $faker->firstName,
            'lastname' => $faker->lastName,
            'dni' => $faker->unique()->numberBetween(20000000, 40000000),
            'cuil' => '20' . $faker->unique()->numberBetween(20000000, 40000000) . '3',
            'faculty' => $faker->word,
            'is_rector' => false,
            'is_dean' => $faker->boolean,
        ]);

        // Crear o obtener la secretaria
        $secretary = $this->secretaryService->getOrCreateSecretary([
            'user_secretaria' => 'SECRETARIA_USER',
            'password_secretaria' => 'SECRETARIA_PASSWORD',
            'email_secretaria' => 'SECRETARIA_EMAIL',
        ]);


        // 10) Crear contrato
        $agreement = Contract::create([
            'signing_date'                 => Carbon::parse($validated['fecha_firma']),
            'url_certificate_afip'         => null,
            'url_statute'                  => null,
            'url_assignment_authorities'   => null,
            'company_id'                   => $company->id,
            'secretary_id'                 => $secretary->id,
            'teacher_id'                   => $teacher->id,
            'creation_date'                => Carbon::parse($validated['fecha_firma']),
            'contact_employee_id'          => $contact_employee->id,
            'representative_employee_id'   => $representative_employee->id,
            'rector'                       => $rector->id,
            'contract_status_id'           => $contract_status->id,
            'type_framework_agreement_id'  => $type->id,
            'file'                         => null // $fullPath ?? null,
        ]);

        


 //------------------------------------------------------GENERACION DE DOCUMENTO------------------------------------------------------------

    function safe($value) {
        return $value ?? '______';
    }

        $template = new TemplateProcessor(storage_path('app/plantillas/convenio_marco.docx'));

        $template->setValue('razon_social', $validated['razon_social'] ?? '________');
        $template->setValue('calle', $validated['calle'] ?? '________');
        $template->setValue('nro_calle', $validated['nro_calle'] ?? '________');
        $template->setValue('ciudad', $validated['localidad'] ?? '________');
        $template->setValue('provincia', safe($validated['provincia']));
        $template->setValue('cuil', safe($validated['cuil_prefijo']) . '-' . safe($validated['cuil_dni']) . '-' . safe($validated['cuil_dv']));
        $template->setValue('cuit', safe($validated['cuit_prefijo']) . '-' . safe($validated['cuit_dni']) . '-' . safe($validated['cuit_dv']));
        $template->setValue('rubro', safe($validated['contraparte_rubro'] ?? null));
        $template->setValue('entidad', safe($validated['entidad']));
        $template->setValue('nombre_rep_contacto',safe($validated['contact_nombre']) . ' ' . safe($validated['contact_apellido']));
        $template->setValue('cargo_rep_contacto', safe($validated['contact_cargo']));
        $template->setValue('nombre_rep_firma', safe($validated['firma_nombre']) . ' ' . safe($validated['firma_apellido']));
        $template->setValue('cargo_rep_firma', safe($validated['firma_cargo']));
        $template->setValue('firma_dni', safe($validated['firma_dni']));
        $template->setValue('rep_firma_empresa_razon_social', safe($validated['firma_empresa_razon_social']));
        $template->setValue('dia', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $template->setValue('mes', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');
        $template->setValue('anio', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('Y') : '____');

        $relativePath = 'convenios_generados/convenios_marcos/' . date('Y/m'); // p.ej. 2025/08
        Storage::makeDirectory($relativePath);

        $nombreArchivo = 'convenio_marco_' . Str::slug($validated['razon_social'] ?? 'sin-razon-social') . '.docx';


        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);
        $template->saveAs($fullPath);

        return view('frameworkAgreement.creationSuccessful', compact('relativePath', 'nombreArchivo'));

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
}


    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        // Si lo necesitás, implementá update usando tus services
        return back()->with('info', 'Update pendiente de implementación.');
    }

    public function destroy(string $id)
    {
        // Si lo necesitás, implementá destroy usando tus services
        return back()->with('info', 'Delete pendiente de implementación.');
    }
}
