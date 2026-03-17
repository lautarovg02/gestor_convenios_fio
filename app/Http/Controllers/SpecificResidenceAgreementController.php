<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use App\Services\CarrerService;
use App\Services\DepartamentService;
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
    protected $carrerService;
    protected $departamentService;

    public function __construct(CompanyService $companyService, StudentService $studentService, CarrerService $carrerService, DepartamentService $departamentService)
    {
        $this->companyService = $companyService;
        $this->studentService = $studentService;
        $this->carrerService = $carrerService;
        $this->departamentService = $departamentService;
    }

    public function create()
    {

        $companies = $this->companyService->getCompaniesByTypeFrameworkAgreement('Convenio Marco de Residencia');
        $carrers = $this->carrerService->getAllCareers();
        $departaments = $this->departamentService->getAllDepartments();
        $students = $this->studentService->getAllStudents();
        return view("specificResidenceAgreement.create", compact('companies', 'carrers', 'departaments', 'students'));
    }

    public function store(StoreSpecificResidenceAgreement $request)
    {
        $data = $request->validated();

        $exists = $this->studentService->existStudentInAgreement($data['studentIdHidden']);

        if ($exists) {
            return redirect()->back()->withInput()->with('StudentWithAgreement', true);
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


            $status = \App\Models\ContractStatus::firstOrCreate(['status' => 'En Coordinación']);

            $agreement = SpecificResidenceAgreement::create([
                'title' => $data['agreementName'],
                'task' => $data['tasks'],
                'signing_date' => $data['fecha_firma'] ?? null,
                'student_id' => $student->id,
                'contract_id' => $data['contract_id'],
                'contract_status_id' => $status->id,
                'file' => $data['file'] ?? null,
                'internship_initial_date' => $data['fecha_inicio'],
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('empresaSeleccionada', true)
                ->with('selectedCompanyId', $request->input('company_id'));
        }

    
        /*----------------------------------------------------crear documento----------------------------------------------------------*/
        
        function safe($value)
        {
            return $value ?? '__';
        }

        // 1. Obtener la empresa seleccionada
        $company = $this->companyService->findCompanyById($data['companyId']);
        Carbon::setLocale('es');
        // 3. Cargar plantilla Word desde storage
        $templatePath = storage_path('app/plantillas/Acuerdo_Específico_de_Residencia.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('nombreEmpresa', $company->company_name);
        $templateProcessor->setValue('nombreAlumno', $student->name . ' ' . $student->last_name);
        $templateProcessor->setValue('dniAlumno', $student->dni);
        $templateProcessor->setValue('nombreCarrera', $data['studentCarrer']);
        $templateProcessor->setValue('tituloResidencia', $data['agreementName']);
        $templateProcessor->setValue('departamentoFacultad', $data['departament']);
        $templateProcessor->setValue('tareasARealizar', $data['tasks']);
        $templateProcessor->setValue('nombreTutorEmpresa', $data['tutorName'] . ' ' . $data['tutorLastName']);
        $templateProcessor->setValue('dniTutorEmpresa', $data['tutorDni']);
        $templateProcessor->setValue('nombreTutorFacu', $data['tutorFacuName'] . ' ' . $data['tutorFacuLastName']);
        $templateProcessor->setValue('dniTutorFacu', $data['tutorFacuDni']);
        $templateProcessor->setValue('diaComienzo', safe($data['fecha_inicio']) ? \Carbon\Carbon::parse($data['fecha_firma'])->format('d') : '____');
        $templateProcessor->setValue('mesComienzo', safe($data['fecha_inicio']) ? \Carbon\Carbon::parse($data['fecha_firma'])->format('m') : '____');
        $templateProcessor->setValue('anioComienzo', safe($data['fecha_inicio']) ? \Carbon\Carbon::parse($data['fecha_firma'])->format('Y') : '____');
        $templateProcessor->setValue('diaFirma', safe($data['fecha_firma']) ? \Carbon\Carbon::parse($data['fecha_firma'])->translatedFormat('d') : '____');
        $templateProcessor->setValue('mesFirma', safe($data['fecha_firma']) ? \Carbon\Carbon::parse($data['fecha_firma'])->translatedFormat('F') : '____');
        $templateProcessor->setValue('anioFirmaEnTexto', safe($data['fecha_firma']) ? \Carbon\Carbon::parse($data['fecha_firma'])->translatedFormat('Y') : '____');
        $templateProcessor->setValue('razonSocialEmpresa', safe($company->denomination));
        $templateProcessor->setValue('repDeEmpresa', safe($data['companyRepresentative']));
        
        // 4. Guardar el documento generado en una carpeta específica

        $relativePath = 'Acuerdos de residencia generados/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Crea la carpeta si no existe

        $nombreArchivo = 'Acuerdo_Específico_de_Residencia_' . Str::slug($data['agreementName']) . '.docx';

        // Asegura que no haya barras duplicadas
        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);

        $templateProcessor->saveAs($fullPath);

        // Guardar la ruta del archivo generado en la DB
        $agreement->update(['file' => $relativePath . '/' . $nombreArchivo]);

        return view('frameworkInternshipAgreement.creationSuccessful', [
            'agreement' => $agreement,
            'download_route' => 'specificResidenceAgreement.download',
            'relativePath' => $relativePath,
            'nombreArchivo' => $nombreArchivo
        ]);
    }




    public function show(string $id)
    {
        $residence = SpecificResidenceAgreement::with(['contract.company', 'contract.secretary.user', 'contract.teacher', 'status', 'student'])->findOrFail($id);
        return view('specificResidenceAgreement.show', compact('residence'));
    }

    public function download($id)
    {
        $agreement = SpecificResidenceAgreement::findOrFail($id);

        if (!$agreement->file) {
            return redirect()->back()->with('error', 'El archivo no está registrado en el sistema.');
        }

        if (!Storage::exists($agreement->file)) {
            return redirect()->back()->with('error', 'El archivo no se encuentra físicamente en el servidor.');
        }

        return Storage::download($agreement->file, basename($agreement->file));
    }
}
