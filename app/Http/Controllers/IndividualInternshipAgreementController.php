<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\StoreIndividualInternshipAgreement;
use App\Models\IndividualInternshipAgreement;
use App\Services\CompanyService;
use App\Models\Student;
use App\Models\City;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\Storage;

class IndividualInternshipAgreementController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    // 1. Mostrar listado de empresas para seleccionar
    public function create()
    {
        $companies = $this->companyService->getCompaniesByTypeFrameworkAgreement('Convenio Marco de Pasantía');
        return view('individualInternshipAgreement.create', compact('companies'));
    }

    // 2. Procesar empresa seleccionada y mostrar formulario con datos precargados
    public function seleccionarEmpresa(Request $request)
    {
        // Buscar la empresa con contrato y empleado relacionados
        $company = Company::with(['contracts.contactEmployee'])->findOrFail($request->company_id);

        // Traemos el contrato
        $contract = $company->contracts->first();
        $empleado = $contract->contactEmployee;
        $city = City::find($company->city_id)?->name ?? '';
        $company->city = $city;

        $representante = [
            'name' => $empleado->name,
            'cuit' => $empleado->cuil,
        ];

    
        return view('individualInternshipAgreement.formularioEmpresa', compact('company', 'representante','contract'));
    }



    // Guardar convenio individual
    public function store(StoreIndividualInternshipAgreement $request)
    {
        $data = $request->validated();

        // --- Buscar o crear estudiante ---
        $cuil = $data['student_cuil_prefijo'] . $data['student_cuil_dni'] . $data['student_cuil_dv'];

        $student = Student::where('dni', $data['student_dni'])
            ->orWhere('email', $data['student_email'])
            ->orWhere('phone_numb', $data['student_phone'])
            ->orWhere('cuil', $cuil)
            ->first();

        if (!$student) {
            $student = Student::create([
                'name'       => $data['student_name'],
                'last_name'  => $data['student_last_name'],
                'dni'        => $data['student_dni'],
                'cuil'       => $cuil,
                'email'      => $data['student_email'],
                'phone_numb' => $data['student_phone'],
                'career'     => $data['student_career'],
            ]);
        }

        // --- Guardar convenio ---
        $agreement = IndividualInternshipAgreement::create([
            'contract_id' => $data['contract_id'],
            'area' => $data['area_pasantia'],
            'assignment' => $data['sitio_pasantia'],
            'task' => $data['tareas'],
            'months_quantity' => $data['periodo_meses'],
            'internship_initial_date' => $data['fecha_inicio'],
            'signing_date' => $data['fecha_convenio'],
            'student_id' => $student->id,
        ]);
              


        // dd($request->all());
        // --- Generar documento Word ---
        $filePath = $this->generateAgreementDocument($agreement, $data);

        // --- Guardar archivo en la BD en el campo file del contrato---
        $agreement->file = $filePath;
        $agreement->save();

        return redirect()->route('individualInternshipAgreement.success', ['id' => $agreement->id]);
    }



    public function success($id)
    {
        $agreement = IndividualInternshipAgreement::findOrFail($id);
        return view('individualInternshipAgreement.success', compact('agreement'));
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

    if (!$agreement->file || !Storage::exists($agreement->file)) {
        return redirect()->back()->with('error', 'Archivo no disponible para descargar.');
    }

    return Storage::download($agreement->file, 'Convenio_Individual_Pasantia_' . $id . '.docx');
}



}
