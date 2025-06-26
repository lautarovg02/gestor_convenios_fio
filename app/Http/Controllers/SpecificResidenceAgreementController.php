<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use App\Http\Requests\StoreSpecificResidenceAgreement;
use App\Models\SpecificResidenceAgreement;
use App\Services\StudentService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpecificResidenceAgreementController extends Controller
{
    protected $companyService;
    protected $studentService;


    public function __construct(CompanyService $companyService, StudentService $studentService)
    {
        $this->companyService = $companyService;
        $this->studentService = $studentService;
    }

    public function create()
    {

        $companies = $this->companyService->getCompaniesByTypeFrameworkAgreement('Convenio Marco de Residencia');

        return view("specificResidenceAgreement.create", compact('companies'));
    }

    public function store(StoreSpecificResidenceAgreement $request)
    {
        $data = $request->validated();

        $exists = SpecificResidenceAgreement::where('contract_id', $data['contract_id'])->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('empresaSeleccionada', false)
                ->with('existeAcuerdo', true);
        }

        try {


            $student = $this->studentService->findOrCreateByDni([
                'name' => $data['studentName'],
                'last_name' => $data['studentLastName'],
                'email' => $data['studentEmail'],
                'phone' => $data['studentCelular'],
                'cuil' => $data['studentCuil'] ?? null,
                'dni' => $data['dniStudent'],
                'carrera' => $data['studentCarrer'],
            ]);



            $agreement = SpecificResidenceAgreement::create([
                'title' => $data['agreementName'],
                'task' => $data['tasks'],
                'signing_date' => $data['fecha_firma'] ?? null,
                'student_id' => $student->id,
                'contract_id' => $data['contract_id'],
                'file' => $data['file'] ?? null,
                'internship_initial_date' => Carbon::now(),
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('empresaSeleccionada', true)
                ->with('selectedCompanyId', $request->input('company_id'));
        }

    
        /*----------------------------------------------------crear documento----------------------------------------------------------*/

        // 3. Cargar plantilla Word desde storage
        $templatePath = storage_path('app/plantillas/Acuerdo_Específico_de_Residencia.docx');
        $templateProcessor = new TemplateProcessor($templatePath);
/*
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
*/

        $relativePath = 'convenios_generados/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Crea la carpeta si no existe

        $nombreArchivo = 'Acuerdo_Específico_de_Residencia' . Str::slug($data['agreementName']) . '.docx';

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
