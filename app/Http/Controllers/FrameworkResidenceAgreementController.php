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

    public function __construct(
        CityService $cityService,
        CompanyService $companyService,
        SecretaryService $secretaryService,
        TeacherService $teacherService,
        EmployeeService $employeeService,
        ContractStatusService $contractStatusService,
        TypeFrameworkAgreementService $typeFrameworkAgreementService,
        ProvinceService $provinceService
    ) {
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
        $this->companyService = $companyService;
        $this->secretaryService = $secretaryService;
        $this->teacherService = $teacherService;
        $this->employeeService = $employeeService;
        $this->contractStatusService = $contractStatusService;
        $this->typeFrameworkAgreementService = $typeFrameworkAgreementService;
    }

    public function create()
    {
        $response = Http::get('https://apis.datos.gob.ar/georef/api/provincias');
        $provincias = $response->json()['provincias'];

        return view("frameworkResidenceAgreement.create", compact('provincias'));
    }

    public function store( StoreFrameworkResidenceAgreement $request)
    {

        $faker = \Faker\Factory::create();
        $validated = $request->validated();


        //------------------------------------ logica para crear convenio --------------------------------------------------------------


        // Crear o obtener la empresa
        $company = $this->companyService->getOrCreateCompany($validated);

        // Crear o obtener la secretaria
        $secretary = $this->secretaryService->getOrCreateSecretary([
            'user_secretaria' => 'SECRETARIA_USER',
            'password_secretaria' => 'SECRETARIA_PASSWORD',
            'email_secretaria' => 'SECRETARIA_EMAIL',
        ]);


        // Crear o obtener el docente
        $teacher = $this->teacherService->findOrCreateByDni([
            'name' => $faker->firstName,
            'lastname' => $faker->lastName,
            'dni' => $faker->unique()->numberBetween(20000000, 40000000),
            'cuil' => '20' . $faker->unique()->numberBetween(20000000, 40000000) . '3',
            'faculty' => $faker->word,
            'is_rector' => false,
            'is_dean' => $faker->boolean,
        ]);

        // Crear o obtener los empleados de contacto y representante
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name' => $validated['contact_nombre'],
            'lastname' => $validated['contact_apellido'],
            'dni' => $validated['contact_dni'],
            'cuil' => $validated['contact_cuil'],
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_celular'],
            'position' => $validated['contact_cargo'],
            'is_represent' => true,
            'company_id' => $company->id,
        ]);

        $representative_employee = $this->employeeService->findOrCreateByDni([
            'name' => $validated['firma_nombre'],
            'lastname' => $validated['firma_apellido'],
            'dni' => $validated['firma_dni'],
            'cuil' => null,
            'email' => $validated['firma_email'],
            'phone' => $validated['firma_celular'] ?? null,
            'position' => $validated['firma_cargo'],
            'is_represent' => true,
            'company_id' => $company->id,
        ]);

        // Crear o obtener el rector
        $rector = $this->teacherService->findOrCreateByDni([
            'name' => $faker->firstName,
            'lastname' => $faker->lastName,
            'dni' => $faker->unique()->numberBetween(20000000, 40000000),
            'cuil' => '20' . $faker->unique()->numberBetween(20000000, 40000000) . '3',
            'faculty' => $faker->word,
            'is_rector' => true,
            'is_dean' => $faker->boolean,
        ]);

        // Crear los estados del contrato
        //el time_limit de los estados se define en horas
        $statuses = [
            ['status' => 'SEVyT (Estado de aprobación/Análisis)', 'time_limit' => 48],
            ['status' => 'SEVyT-Firma', 'time_limit' => 48],
            ['status' => 'En ejecución', 'time_limit' => null],
            ['status' => 'Finalizado', 'time_limit' => null],
            ['status' => 'En departamento/En coordinación', 'time_limit' => 48],
            ['status' => 'SEVyT (Estado de aprobación)', 'time_limit' => 48],
            ['status' => 'SEVyT-Firma (particular)', 'time_limit' => 48],
            ['status' => 'Contraparte', 'time_limit' => 360], // 15 días * 24h
            ['status' => 'SEVyT-Enviar a CA', 'time_limit' => 24],
            ['status' => 'En CA', 'time_limit' => null],
        ];
        // Crear el primer estado del contrato
        $contract_status = $this->contractStatusService->createStatus($statuses[0]);

        // Crear o encontrar el tipo de convenio marco
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco de Residencia');

        // Crear el contrato
        $agreement = Contract::create([
            'signing_date' => Carbon::parse($validated['fecha_firma']),
            'url_certificate_afip' => null,
            'url_statute' => null,
            'url_assignment_authorities' => null,
            'company_id' => $company->id,
            'secretary_id' => $secretary->id,
            'teacher_id' => $teacher->id,
            'contact_employee_id' => $contact_employee->id,
            'representative_employee_id' => $representative_employee->id,
            'rector' => $rector->id,
            'contract_status_id' => $contract_status->id,
            'type_framework_agreement_id' => $type->id,
            'file' => null,
            'creation_date' => Carbon::now(),
        ]);


        //------------------------------------------------------GENERACION DE DOCUMENTO------------------------------------------------------------

        
        $companyCity = $this->cityService->findCityById($company->city_id);
        $companyProvince = $this->provinceService->getById($companyCity->province_id);
        $companyRepresentativeEmployee = $this->companyService->findCompanyById($representative_employee->company_id);


        // 3. Cargar plantilla Word desde storage
        $templatePath = storage_path('app/plantillas/Convenio_Marco_de_Residencia.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Seteo de valores en el template
        $templateProcessor->setValue('razon_social', $company->denomination);
        $templateProcessor->setValue('calle', $company->street);
        $templateProcessor->setValue('nro_calle', $company->number);
        $templateProcessor->setValue('ciudad', $companyCity->name);
        $templateProcessor->setValue('provincia', $companyProvince->name);
        $templateProcessor->setValue('cuit_empresa', $company->cuit);
       
        $templateProcessor->setValue('nombre_rep_contacto', $contact_employee->name . ' ' . $contact_employee->lastname);
        $templateProcessor->setValue('cargo_rep_contacto', $contact_employee->position);
        $templateProcessor->setValue('dni_rep_contacto', $contact_employee->dni);
       
        $templateProcessor->setValue('nombre_rep_firma', $representative_employee->name . ' ' . $representative_employee->lastname);
        $templateProcessor->setValue('cargo_rep_firma', $representative_employee->position);
        $templateProcessor->setValue('rep_firma_empresa_razon_social',  $companyRepresentativeEmployee->denomination);
       
        $templateProcessor->setValue('dia', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $templateProcessor->setValue('mes', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');
        $templateProcessor->setValue('anio', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('Y') : '____');


        $relativePath = 'convenios_generados/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Crea la carpeta si no existe

        $nombreArchivo = 'convenio_marco_residencia_' . Str::slug($validated['razon_social']) . '.docx';

        // Asegura que no haya barras duplicadas
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
}
