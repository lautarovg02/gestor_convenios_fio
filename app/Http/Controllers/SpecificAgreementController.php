<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecificAgreementRequest; // Asegúrate de que este sea el nombre correcto de tu request
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\Specific;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\Student;
use App\Services\CompanyService;
use App\Services\ContractService;
use App\Services\StudentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpecificAgreementController extends Controller
{

    protected $companyService;
    protected $contractService;
    protected $studentService;

    public function __construct(ContractService $contractService, CompanyService $companyService, StudentService $studentService)
    {
        $this->studentService = $studentService;
        $this->companyService = $companyService;
        // Inyectar el servicio de contratos
        $this->contractService = $contractService;
    }



   public function create()
{
    // Buscamos empresas que tengan contratos Marco que NO estén Finalizados o Deshabilitados
 $companies = Company::whereHas('contracts', function ($query) {
     $query->whereHas('typeFrameworkAgreement', function ($q) {
         $q->where('type', 'Convenio Marco');
     })->whereHas('status', function ($q) {
         $q->whereNotIn('status', ['Finalizado', 'Deshabilitado']);
     });
 })
 ->with(['contracts' => function ($query) {
     // También filtramos la carga para que el select solo vea los disponibles
     $query->whereHas('typeFrameworkAgreement', function ($q) {
         $q->where('type', 'Convenio Marco');
     })->whereHas('status', function ($q) {
         $q->whereNotIn('status', ['Finalizado', 'Deshabilitado']);
     });
 }])
    ->get(['id', 'denomination', 'company_name', 'cuit']);

    $students = Student::all();
    $teachers = \App\Models\Teacher::orderBy('lastname')->get();

    return view('specificAgreement.create', compact('companies', 'students', 'teachers'));
}




    public function getFrameworkAgreementData($contractId)
    {
        $contract = Contract::with([
            'company.city',
            'company.entity',
            'company.employees',
            'contactEmployee.phones',
            'representativeEmployee.phones'

        ])->findOrFail($contractId);


        return response()->json([
            // Contraparte
            'razon_social'      => $contract->company->denomination ?? '',
            'ambito'            => $contract->company->scope ?? '',
            'cuit'              => $contract->company->cuit ?? '',
            'rubro'             => $contract->company->sector ?? '',
            'confidencialidad'  => $contract->confidentiality ?? false,

            // Dirección
            'direccion' => [
                'empresa_calle'         => $contract->company->street ?? '',
                'empresa_numero'        => $contract->company->number ?? '',
                'empresa_ciudad'        => $contract->company->city->name ?? '',
                'empresa_codigo_postal' => $contract->company->city->postal_code ?? '',
                'empresa_provincia'     => $contract->company->city->province->name ?? '',
                'pais'          => $contract->company->country ?? '',
            ],

            // Contacto
            'contacto' => [
                'nombre'    => $contract->contactEmployee->name ?? '',
                'apellido'  => $contract->contactEmployee->lastname ?? '',
                'cargo'     => $contract->contactEmployee->position ?? '',
                'dni'       => $contract->contactEmployee->dni ?? '',
                'celular'   => $contract->contactEmployee->phones->first()->number ?? '',
                'email'     => $contract->contactEmployee->email ?? '',
            ],

            // Firma
            'firma' => [
                'nombre'    => $contract->representativeEmployee->name ?? '',
                'apellido'  => $contract->representativeEmployee->lastname ?? '',
                'dni'       => $contract->representativeEmployee->dni ?? '',
                'email'     => $contract->representativeEmployee->email ?? '',
                'celular' => $contract->representativeEmployee->phones->first()->phone_number ?? '',
                'cargo'     => $contract->representativeEmployee->position ?? '',
            ],

            // Lugar y Fecha
            'lugar' => $contract->company->city->name ?? '',
            'fecha' => $contract->signing_date
                ? \Carbon\Carbon::parse($contract->signing_date)->format('Y-m-d')
                : now()->format('Y-m-d'),
                
            // Empleados de la empresa
            'empleados' => $contract->company->employees->map(function ($emp) {
                return [
                    'id' => $emp->id,
                    'nombre' => $emp->name . ' ' . $emp->lastname,
                    'cargo' => $emp->position
                ];
            }),
        ]);
    }



    public function index()
    {
        //
    }




    public function store(StoreSpecificAgreementRequest $request)
    {
        // Validar los datos requeridos
        $validated =  $request->validated();


        $exists = $this->studentService->existStudentInAgreement($validated['student_id']);

        if ($exists) {
            return redirect()->back()->withInput()->with('StudentWithAgreement', true);
        }

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('specific_files', 'public');
            $validated['file'] = $filePath;
        }

        $status = \App\Models\ContractStatus::firstOrCreate(['status' => 'En Departamento']);

        // Crear convenio específico en la base de datos
        $convenio = Specific::create([
            'contract_id' => $validated['contract_id'],
            'contract_status_id' => $status->id,
            'signing_date' => $validated['fecha_firma'],
            'objective' => $validated['objetivo'],
            'commitment_parties' => $validated['compromisos'],
            'responsable_control_company' => $validated['responsable_control_company'] ?? null,
            'responsable_control_fio' => $validated['responsable_control_fio'] ?? null,
            //file
            'file' => $validated['file'] ?? null,
        ]);


        // Guardar en tabla intermedia
        $convenio->students()->attach($request->student_id, [
            'specific_contract_id' => $convenio->contract_id
        ]);




        // Crear archivo Word
        $template = new TemplateProcessor(storage_path('app/plantillas/convenio_especifico.docx'));

        $template->setValue('razon_social', $validated['razon_social']);
        $template->setValue('nominacion', $validated['razon_social']); // mismo valor

        $template->setValue('calle', $validated['empresa_calle'] ?? 'falsa  ');
        $template->setValue('nro', $validated['empresa_numero'] ?? '123 ');
        $template->setValue('ciudad', $validated['empresa_ciudad'] ?? 'Olavarria');
        $template->setValue('provincia', $validated['empresa_provincia'] ?? 'Buenos Aires');

        $template->setValue('nombre_rep_firma', ($validated['firma_nombre'] ?? '') . ' ' . ($validated['firma_apellido'] ?? ''));
        $template->setValue('dni_rep_firma', $validated['firma_dni'] ?? '________');
        $template->setValue('email_rep_firma', $validated['firma_email'] ?? '________');
        $template->setValue('cargo_rep_firma', $validated['firma_cargo'] ?? '________');

        $template->setValue('lugar_firma', $validated['lugar_firma']);
        $template->setValue('dia_firma', \Carbon\Carbon::parse($validated['fecha_firma'])->format('d'));
        $template->setValue('mes_firma', \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F'));
        $template->setValue('anio_firma', \Carbon\Carbon::parse($validated['fecha_firma'])->format('Y'));


        /*Agregar Becario*/
        $template->setValue('becario', $validated['becario'] ?? '________');


        $template->setValue('objetivo', $validated['objetivo'] ?? '________');
        $template->setValue('compromisos', $validated['compromisos'] ?? '________');

        // Combina nombre + apellido del responsable empresa si no tenés el campo unificado
        $template->setValue('responsable_empresa', $validated['responsable_control_company'] ?? '________');

        // Campo directo desde el form
        $template->setValue('responsable_fio', $validated['responsable_control_fio'] ?? '________');

        // Si el campo "becario" existe, se usa. Si no, pone '________'
        $template->setValue('becario', $validated['becario'] ?? '________');



        $relativePath = 'convenios_generados/Convenio_especifico/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Crea la carpeta si no existe

        $nombreArchivo = 'convenio_especifico_' . Str::slug($validated['razon_social']) . '.docx';

        // Asegura que no haya barras duplicadas
        $fullPath = storage_path('app/' . trim($relativePath, '/') . '/' . $nombreArchivo);

        $template->saveAs($fullPath);
        $convenio->save();

        // Opción de descargar


        // Guardar la ruta del archivo generado en la DB
        $convenio->update(['file' => $relativePath . '/' . $nombreArchivo]);

        // Redirigir al main
        return view('frameworkInternshipAgreement.creationSuccessful', [
            'agreement' => $convenio,
            'download_route' => 'specificAgreement.download',
            'relativePath' => $relativePath,
            'nombreArchivo' => $nombreArchivo
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specific = Specific::with(['contract.company', 'contract.secretary.user', 'contract.teacher', 'status', 'students'])->findOrFail($id);
        return view('specificAgreement.show', compact('specific'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function download($id)
    {
        $agreement = Specific::findOrFail($id);

        if (!$agreement->file) {
            return redirect()->back()->with('error', 'El archivo no está registrado en el sistema.');
        }

        if (!Storage::exists($agreement->file)) {
            return redirect()->back()->with('error', 'El archivo no se encuentra físicamente en el servidor.');
        }

        return Storage::download($agreement->file, basename($agreement->file));
    }
}
