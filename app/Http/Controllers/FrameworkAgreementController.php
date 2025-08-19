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

        // Si querés evitar la API, podés mockear provincias acá:
        $provincias = [
            ['id' => '02', 'nombre' => 'Ciudad Autónoma de Buenos Aires'],
            ['id' => '06', 'nombre' => 'Buenos Aires'],
            ['id' => '10', 'nombre' => 'Catamarca'],
            ['id' => '14', 'nombre' => 'Córdoba'],
            ['id' => '18', 'nombre' => 'Chaco'],
            ['id' => '22', 'nombre' => 'Chubut'],
            ['id' => '26', 'nombre' => 'Entre Ríos'],
            ['id' => '30', 'nombre' => 'Formosa'],
            ['id' => '34', 'nombre' => 'Jujuy'],
            // ...completá si querés todas
        ];

        return view('frameworkAgreement.create', compact('provincias', 'companies'));
    }


    public function store(ConvenioMarcoRequest $request)
    {
        $validated = $request->validated();

        // 1) Tipo de convenio marco (NO pasantía)
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco');


        function safe($value)
        {
            return $value ?? '______';
        }

        $template = new TemplateProcessor(
            storage_path('app/plantillas/convenio_marco.docx') // <- tu plantilla de marco
        );

        // Ejemplos de setValue (ajustá SOLO las keys de plantilla a las que ya uses en marco)
        $template->setValue('razon_social', $validated['razon_social'] ?? '________');
        //            $template->setValue('nominacion',   $validated['razon_social'] ?? '________'); // NO VA 

        // Dirección de la empresa
        $template->setValue('calle',     $validated['empresa_calle']    ?? '________');
        $template->setValue('nro_calle',       $validated['empresa_numero']   ?? '________');
        $template->setValue('ciudad',    $validated['localidad']   ?? '________');

        // CUIL/CUIT
        $template->setValue(
            'cuil',
            safe($validated['cuil_prefijo']) . '-' . safe($validated['cuil_dni']) . '-' . safe($validated['cuil_dv'])
        );
        $template->setValue(
            'cuit',
            safe($validated['cuit_prefijo']) . '-' . safe($validated['cuit_dni']) . '-' . safe($validated['cuit_dv'])
        );


        // rubro, entidad
        $template->setValue('rubro', safe($validated['contraparte_rubro'] ?? null));
        $template->setValue('entidad', safe($validated['entidad']));
        //$templateProcessor->setValue('dedicacion', safe($validated['dedicacion']));

        // Representante de contacto
        $template->setValue(
            'nombre_rep_contacto',
            safe($validated['contact_nombre']) . ' ' . safe($validated['contact_apellido'])
        );
        $template->setValue('cargo_rep_contacto', safe($validated['contact_cargo']));


        // Representante de firma
        $template->setValue(
            'nombre_rep_firma',
            safe($validated['firma_nombre']) . ' ' . safe($validated['firma_apellido'])
        );
        $template->setValue('cargo_rep_firma', safe($validated['firma_cargo']));
        $template->setValue('firma_dni', safe($validated['firma_dni']));
        $template->setValue(
            'rep_firma_empresa_razon_social',
            safe($validated['firma_empresa_razon_social'])
        );

        $template->setValue(
            'dia',
            !empty($validated['fecha_firma'])
                ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d')
                : '____'
        );

        $template->setValue(
            'mes',
            !empty($validated['fecha_firma'])
                ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F')
                : '____'
        );

        // Año (numérico completo, ej: 2025)
        $template->setValue(
            'anio',
            !empty($validated['fecha_firma'])
                ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('Y')
                : '____'
        );



        // Si viene empresa seleccionada, validamos que no exista otro convenio marco
        $companyIdForCheck = $validated['company_id'] ?? null;
        if ($companyIdForCheck) {
            $exists = $this->contractService
                ->getFrameworkAgreementsByCompany($companyIdForCheck, $type->id);
            if ($exists->isNotEmpty()) {
                return back()->withErrors(['errorExistsContract' => 'Ya existe un Convenio Marco para esta empresa.'])
                    ->withInput();
            }
        }

        $relativePath = 'convenios_generados/' . date('Y/m'); // p.ej. 2025/08
        Storage::makeDirectory($relativePath);

        $nombreArchivo = 'convenio_marco_' . Str::slug($validated['razon_social'] ?? 'sin-razon-social') . '.docx';


        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);
        $template->saveAs($fullPath);


        // 2) Crear u obtener empresa (usa direcciones/ciudad del form si aplica)
        // El service debería crear o devolver la empresa en base a los datos del form
        $company = $this->companyService->getOrCreateCompany($validated);



        // 5) Empleado de contacto
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name'        => $validated['contact_nombre'],
            'lastname'    => $validated['contact_apellido'],
            'dni'         => $validated['contact_dni'],
            'cuil'        => $validated['contact_cuil'] ?? (
                ($validated['cuil_prefijo'] ?? '') .
                ($validated['cuil_dni'] ?? '') .
                ($validated['cuil_dv'] ?? '')
            ),
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

        // 9) (OPCIONAL) Generación de archivo Word: si ya lo tenías, integrá acá tu TemplateProcessor
        // $fullPath = $this->tuServicioDeWord->generarConvenioMarco($validated, ...);

        // 10) Crear contrato
        $agreement = Contract::create([
            'signing_date'                 => Carbon::parse($validated['fecha_firma']),
            'url_certificate_afip'         => null,
            'url_statute'                  => null,
            'url_assignment_authorities'   => null,
            'company_id'                   => $company->id,
            'secretary_id'                 => 10,
            'teacher_id'                   => 58,
            'creation_date'                => Carbon::parse($validated['fecha_firma']),
            'contact_employee_id'          => $contact_employee->id,
            'representative_employee_id'   => $representative_employee->id,
            'rector'                       => 54,
            'contract_status_id'           => $contract_status->id,
            'type_framework_agreement_id'  => $type->id,
            'file'                         => null // $fullPath ?? null,
        ]);

        return view('frameworkAgreement.creationSuccessful', compact('relativePath', 'nombreArchivo'));
    }





    /*    
    public function store(ConvenioMarcoRequest $request)
    {
        $validated = $request->validated();

        // 1) Tipo de convenio marco (NO pasantía)
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco');


        function safe($value)
        {
            return $value ?? '______';
        }




        $relativePath = 'convenios_generados/' . date('Y/m');   // <— igual que Específico
        Storage::makeDirectory($relativePath);

        $nombreArchivo = 'convenio_marco_' . Str::slug($validated['razon_social']) . '.docx';

        $fullPath = storage_path('app/'.$relativePath.'/'.$nombreArchivo);

        // Cargar plantilla
        $templatePath = storage_path('app/plantillas/Convenio Marco.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Asignar valores
        $templateProcessor->setValue('razon_social', safe($validated['razon_social']));
        $templateProcessor->setValue('calle', safe($validated['calle']));
        $templateProcessor->setValue('nro_calle', safe($validated['nro_calle']));
        $templateProcessor->setValue('ciudad', safe($validated['localidad']));

        // CUIL/CUIT
        $templateProcessor->setValue(
            'cuil',
            safe($validated['cuil_prefijo']) . '-' . safe($validated['cuil_dni']) . '-' . safe($validated['cuil_dv'])
        );
        $templateProcessor->setValue(
            'cuit',
            safe($validated['cuit_prefijo']) . '-' . safe($validated['cuit_dni']) . '-' . safe($validated['cuit_dv'])
        );

        // Provincia, rubro, entidad, dedicación
        $templateProcessor->setValue('provincia', safe($validated['provincia']));
        $templateProcessor->setValue('rubro', safe($validated['contraparte_rubro'] ?? null));
        $templateProcessor->setValue('entidad', safe($validated['entidad']));
        //$templateProcessor->setValue('dedicacion', safe($validated['dedicacion']));

        // Representante de contacto
        $templateProcessor->setValue(
            'nombre_rep_contacto',
            safe($validated['contact_nombre']) . ' ' . safe($validated['contact_apellido'])
        );
        $templateProcessor->setValue('cargo_rep_contacto', safe($validated['contact_cargo']));

        // Representante de firma
        $templateProcessor->setValue(
            'nombre_rep_firma',
            safe($validated['firma_nombre']) . ' ' . safe($validated['firma_apellido'])
        );
        $templateProcessor->setValue('cargo_rep_firma', safe($validated['firma_cargo']));
        $templateProcessor->setValue('firma_dni', safe($validated['firma_dni']));
        $templateProcessor->setValue(
            'rep_firma_empresa_razon_social',
            safe($validated['firma_empresa_razon_social'])
        );

        $templateProcessor->setValue(
            'dia',
            !empty($validated['fecha_firma'])
                ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d')
                : '____'
        );

        $templateProcessor->setValue(
            'mes',
            !empty($validated['fecha_firma'])
                ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F')
                : '____'
        );

        // Año (numérico completo, ej: 2025)
        $templateProcessor->setValue(
            'anio',
            !empty($validated['fecha_firma'])
                ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('Y')
                : '____'
        );

        // Si viene empresa seleccionada, validamos que no exista otro convenio marco
        $companyIdForCheck = $validated['company_id'] ?? null;
        if ($companyIdForCheck) {
            $exists = $this->contractService
                ->getFrameworkAgreementsByCompany($companyIdForCheck, $type->id);
            if ($exists->isNotEmpty()) {
                return back()->withErrors(['errorExistsContract' => 'Ya existe un Convenio Marco para esta empresa.'])
                    ->withInput();
            }
        }

        $templateProcessor->saveAs($fullPath);


        // 2) Crear u obtener empresa (usa direcciones/ciudad del form si aplica)
        // El service debería crear o devolver la empresa en base a los datos del form
        $company = $this->companyService->getOrCreateCompany($validated);



        // 5) Empleado de contacto
        $contact_employee = $this->employeeService->findOrCreateByDni([
            'name'        => $validated['contact_nombre'],
            'lastname'    => $validated['contact_apellido'],
            'dni'         => $validated['contact_dni'],
            'cuil'        => $validated['contact_cuil'] ?? (
                ($validated['cuil_prefijo'] ?? '') .
                ($validated['cuil_dni'] ?? '') .
                ($validated['cuil_dv'] ?? '')
            ),
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
            'position'    => $validated['firma_cargo'] ?? null,
            'is_represent' => true,
            'company_id'  => $company->id,
        ]);





        // 8) Estado inicial del contrato
        $contract_status = $this->contractStatusService->createStatus([
            'status'     => 'SEVyT (Estado de aprobación/Análisis)',
            'time_limit' => 48, // horas
        ]);

        // 9) (OPCIONAL) Generación de archivo Word: si ya lo tenías, integrá acá tu TemplateProcessor
        // $fullPath = $this->tuServicioDeWord->generarConvenioMarco($validated, ...);

        // 10) Crear contrato
        $agreement = Contract::create([
            'signing_date'                 => Carbon::parse($validated['fecha_firma']),
            'url_certificate_afip'         => null,
            'url_statute'                  => null,
            'url_assignment_authorities'   => null,
            'company_id'                   => $company->id,
            'secretary_id'                 => 10,
            'teacher_id'                   => 58,
            'creation_date'                => Carbon::parse($validated['fecha_firma']),
            'contact_employee_id'          => $contact_employee->id,
            'representative_employee_id'   => $representative_employee->id,
            'rector'                       => 54,
            'contract_status_id'           => $contract_status->id,
            'type_framework_agreement_id'  => $type->id,
            'file'                         => null // $fullPath ?? null,
        ]);

        $downloadUrl = route('agreement.download', ['file' => $nombreArchivo]);
        return view('frameworkAgreement.creationSuccessful', compact('nombreArchivo','downloadUrl'));
        

    }
*/
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
