<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\StoreIndividualInternshipAgreement;
use App\Models\IndividualInternshipAgreement;
use App\Services\CompanyService;
use App\Services\StudentService;
use App\Models\Student;
use App\Models\City;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\Storage;

class IndividualInternshipAgreementController extends Controller
{
    protected CompanyService $companyService;
    protected StudentService $studentService;

    public function __construct(CompanyService $companyService, StudentService $studentService)
    {
        $this->companyService = $companyService;
        $this->studentService = $studentService;
    }

    // 0. Listado — redirige al índice general
    public function index()
    {
        return redirect()->route('agreements.index');
    }

    // 1. Mostrar listado de empresas para seleccionar
    public function create()
    {
        $companies = $this->companyService->getCompaniesByTypeFrameworkAgreement('Convenio Marco de Pasantía');
        return view('individualInternshipAgreement.create', compact('companies'));
    }

    // 2. Procesar empresa seleccionada y mostrar formulario con datos precargados
    public function selectCompany(Request $request)
    {
      // ✅ Guardamos los datos que necesitamos pasar
    $company_id = $request->company_id;
    $student_id = $request->student_id;

    // 🔄 Redirigimos a una ruta GET
    return redirect()->route('individual-internship-agreements.fill-form', [
        'company_id' => $company_id,
        'student_id' => $student_id,
    ]);
    }

    public function fillForm(Request $request)
    {
        // Recuperamos empresa y contrato
        $company = Company::with(['contracts.contactEmployee'])->findOrFail($request->company_id);
        $contract = $company->contracts->first();
        $empleado = $contract->contactEmployee;
        $city = City::find($company->city_id)?->name ?? '';
        $company->city = $city;

        $representante = [
            'name' => $empleado->name,
            'cuit' => $empleado->cuil,
        ];

        // Recuperamos alumno
        $student = null;
        if ($request->filled('student_id')) {
            $student = Student::findOrFail($request->student_id);
        }

        // Cargamos todos los docentes para el select de docente responsable
        $teachers = \App\Models\Teacher::orderBy('lastname')->orderBy('name')->get();

        // Cargamos los empleados de la empresa para el select de tutor
        $employees = $company->employees()->orderBy('name')->get();

        return view('individualInternshipAgreement.formularioEmpresa',
            compact('company', 'representante', 'contract', 'student', 'teachers', 'employees'));
    }



    // Guardar convenio individual

    public function store(StoreIndividualInternshipAgreement $request)
    {
        $data = $request->validated();

        $exists = $this->studentService->existStudentInAgreement($data['student_id']);
        if ($exists) {
            return back()
                ->withErrors(['Ya existe un convenio individual para el alumno seleccionado.'])
                ->withInput();
        }

        // Resolver tutor (empleado de la empresa) y docente (docente FIO) desde IDs
        $tutor   = \App\Models\Employee::findOrFail($data['tutor_employee_id']);
        $docente = \App\Models\Teacher::findOrFail($data['docente_teacher_id']);

        // Construir los campos nome/cuil que usa generateAgreementDocument
        $data['tutor_empresa'] = $tutor->name . ' ' . $tutor->lastname;
        [$tp, $td, $tv] = $this->splitCuil($tutor->cuil ?? '');
        $data['tutor_cuil_prefijo'] = $tp;
        $data['tutor_cuil_dni']     = $td;
        $data['tutor_cuil_dv']      = $tv;

        $data['docente_nombre'] = $docente->name . ' ' . $docente->lastname;
        [$dp, $dd, $dv] = $this->splitCuil($docente->cuil ?? '');
        $data['docente_cuil_prefijo'] = $dp;
        $data['docente_cuil_dni']     = $dd;
        $data['docente_cuil_dv']      = $dv;

        $status = \App\Models\ContractStatus::firstOrCreate(['status' => 'En Coordinación']);

        $agreement = IndividualInternshipAgreement::create([
            'contract_id'            => $data['contract_id'],
            'contract_status_id'     => $status->id,
            'area'                   => $data['area_pasantia'],
            'assignment'             => $data['sitio_pasantia'],
            'task'                   => $data['tareas'],
            'months_quantity'        => $data['periodo_meses'],
            'internship_initial_date'=> $data['fecha_inicio'],
            'signing_date'           => $data['fecha_convenio'],
            'student_id'             => $data['student_id'],
            'tutor_employee_id'      => $tutor->id,
            'docente_teacher_id'     => $data['docente_teacher_id'],
        ]);

        $filePath = $this->generateAgreementDocument($agreement, $data);
        $agreement->file = $filePath;
        $agreement->save();

        [$relativePath, $nombreArchivo] = [dirname($filePath), basename($filePath)];
        return view('individualInternshipAgreement.creationSuccessful',
            compact('agreement', 'relativePath', 'nombreArchivo'));
    }

    /** Parte un CUIL (XX-XXXXXXXX-X) en prefijo, dni, dígito verificador. */
    private function splitCuil(string $cuil): array
    {
        $clean = preg_replace('/\D/', '', $cuil);
        return [
            substr($clean, 0, 2),
            substr($clean, 2, strlen($clean) - 3),
            substr($clean, -1),
        ];
    }


    public function success($id)
    {
        $agreement = IndividualInternshipAgreement::findOrFail($id);
        return view('individualInternshipAgreement.success', compact('agreement'));
    }

    public function show($id)
{
    $agreement = IndividualInternshipAgreement::with(['contract.company', 'contract.secretary.user', 'contract.teacher', 'status', 'student'])->findOrFail($id);
    return view('individualInternshipAgreement.show', compact('agreement'));
}



    private function generateAgreementDocument($agreement, $data)
    {
        // Ruta de la plantilla (relativa a storage/app)
        $templatePath = storage_path('app/plantillas/Convenio_individual_pasantia.docx');

        // Crear instancia TemplateProcessor
        $template = new TemplateProcessor($templatePath);

        $fechaConvenio = Carbon::parse($data['fecha_convenio'] ?? now());
        $fechaInicio = Carbon::parse($data['fecha_inicio'] ?? now());
        $fechaConvenioIndividual =Carbon::parse($data['fecha_firma_marco'] ?? now());

        // Preparar las variables para reemplazar
        $variables = [
            'nombreEmpresa'          => $data['company_denomination'] ?? '',
            'sector'                 => $data['company_sector'] ?? '',
            'domicilioEmpresa'       => $data['company_street'] ?? '',
            'numeroDomicilioEmpresa' => $data['company_number'] ?? '',
            'ciudadEmpresa'          => $data['company_city'] ?? '',
            'representanteEmpresa'   => $data['company_representante'] ?? '',
            'cuitRepresentante'      => $data['company_representante_cuit'] ?? '',


            // Pasante
            'nombreApellidoPasante'  => $data['student_name'] . ' ' . $data['student_last_name'],
            'cuilPasante'            => $data['student_cuil_prefijo'] . '-' . $data['student_cuil_dni'] . '-' . $data['student_cuil_dv'],
            'domicilioPasante'       => $data['student_domicilio_calle'] ?? '',
            'numeroDomicilioPasante' => $data['student_domicilio_numero'] ?? '',
            'ciudadPasante'          => $data['student_ciudad'] ?? '',


            //Datos pasantia
            'areaPasantia'           => $data['area_pasantia'] ?? '',
            'sitioPasantia'          => $data['sitio_pasantia'] ?? '',
            'tareas'                 => $data['tareas'] ?? '',
            'fechaCovenioIndividual'    => $fechaConvenioIndividual->translatedFormat('j \d\e F \d\e Y'),

            'mesesPasantiaNumero'  => $data['periodo_meses'] ?? '',
            'mesesPasantiaLetra'   => $this->numberToWords($data['periodo_meses'] ?? 0), // función para convertir número a letras (podés implementarla)

           // 'fechaInicioPasantia'    => $data['fecha_inicio'] ?? '',
            'fechaInicioPasantia' => $fechaInicio->translatedFormat('j \d\e F \d\e Y'),
            'nombreApellidoTutor'   => $data['tutor_empresa'] ?? '',
            'cuilTutor'              => $data['tutor_cuil_prefijo'] . '-' . $data['tutor_cuil_dni'] . '-' . $data['tutor_cuil_dv'],

            'nombreApellidoDocente' => $data['docente_nombre'] ?? '',
            'cuilDocente'            => $data['docente_cuil_prefijo'] . '-' . $data['docente_cuil_dni'] . '-' . $data['docente_cuil_dv'],

            'remuneracionLetras'   => $this->numberToWords($data['remuneracion_monto'] ?? 0),
            'remuneracionNumero'   => number_format($data['remuneracion_monto'] ?? 0, 2, ',', '.'),

            'diaFechaconvenio'           => $fechaConvenio->format('j'),
            'mesenLetraFechaconvenio'    => $fechaConvenio->translatedFormat('F'), // "julio"
            'anioennumeroFechaconvenio'  => $fechaConvenio->format('Y'),
            'lugar_convenio'         => $data['lugar_convenio'] ?? '',
        ];

        // Reemplazar variables en la plantilla
        foreach ($variables as $key => $value) {
            $template->setValue($key, $value);
        }

        // Generar nombre archivo
        $fileName = 'convenio_individual_pasantia_' . $agreement->id . '_' . time() . '.docx';

        $dir = storage_path('app/convenios_generados');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileName = 'convenio_individual_pasantia_' . $agreement->id . '_' . time() . '.docx';

        $savePath = 'convenios_generados/' . $fileName;

        $template->saveAs(storage_path('app/' . $savePath));


        return $savePath; // Retornás la ruta para guardar en BD

    }

    private function numberToWords($number)
    {
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('es');
    
        return $numberTransformer->toWords($number);
    }





    public function download($id)
    {
        $agreement = IndividualInternshipAgreement::findOrFail($id);

        if (!$agreement->file) {
            return redirect()->back()->with('error', 'El archivo no está registrado en el sistema.');
        }

        if (!Storage::exists($agreement->file)) {
            return redirect()->back()->with('error', 'El archivo no se encuentra físicamente en el servidor.');
        }

        return Storage::download($agreement->file, 'Convenio_Individual_Pasantia_' . $id . '.docx');
    }



}