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

        return view('frameworkAgreement.create', compact('provincias','companies' ));
    }

    public function store(ConvenioMarcoRequest $request)
    {
        $validated = $request->validated();

        // 1) Tipo de convenio marco (NO pasantía)
        $type = $this->typeFrameworkAgreementService->findOrCreateByType('Convenio Marco');

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

        // 2) Crear u obtener empresa (usa direcciones/ciudad del form si aplica)
        // El service debería crear o devolver la empresa en base a los datos del form
        $company = $this->companyService->getOrCreateCompany($validated);

        // 3) Secretaria (usa getOrCreate para no repetir)
        $secretary = $this->secretaryService->getOrCreateSecretary([
            'user_secretaria'      => 'SECRETARIA_USER',
            'password_secretaria'  => 'SECRETARIA_PASSWORD',
            'email_secretaria'     => 'SECRETARIA_EMAIL',
        ]);

        // 4) Docente (no rector)
        $teacher = $this->teacherService->findOrCreateByDni([
            'name'      => $validated['teacher_name']     ?? fake()->firstName(),
            'lastname'  => $validated['teacher_lastname'] ?? fake()->lastName(),
            'dni'       => $validated['teacher_dni']      ?? fake()->unique()->numberBetween(20000000, 40000000),
            'cuil'      => $validated['teacher_cuil']     ?? ('20' . fake()->unique()->numberBetween(20000000, 40000000) . '3'),
            'faculty'   => $validated['teacher_faculty']  ?? 'Facultad X',
            'is_rector' => false,
            'is_dean'   => false,
        ]);

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
            'phone'       => $validated['contact_celular'] ?? null,
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
            'phone'       => $validated['firma_celular'] ?? null,
            'position'    => $validated['firma_cargo'] ?? null,
            'is_represent' => true,
            'company_id'  => $company->id,
        ]);

        // 7) Rector (sí rector)
        $rector = $this->teacherService->findOrCreateByDni([
            'name'      => fake()->firstName(),
            'lastname'  => fake()->lastName(),
            'dni'       => fake()->unique()->numberBetween(20000000, 40000000),
            'cuil'      => '20' . fake()->unique()->numberBetween(20000000, 40000000) . '3',
            'faculty'   => fake()->word(),
            'is_rector' => true,
            'is_dean'   => fake()->boolean(),
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
            'secretary_id'                 => $secretary->id,
            'teacher_id'                   => $teacher->id,
            'contact_employee_id'          => $contact_employee->id,
            'representative_employee_id'   => $representative_employee->id,
            'rector'                       => $rector->id,
            'contract_status_id'           => $contract_status->id,
            'type_framework_agreement_id'  => $type->id,
            'file'                         => null, // $fullPath ?? null,
            'creation_date'                => Carbon::parse($validated['fecha_firma']),
        ]);

        // Redirigir a una vista de éxito o al show
        return redirect()
            ->route('frameworkAgreement.show', $agreement->id)
            ->with('success', 'Convenio Marco creado correctamente.');
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
