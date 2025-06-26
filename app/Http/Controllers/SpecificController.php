<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecificAgreementRequest; // Asegúrate de que este sea el nombre correcto de tu request
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\FrameworkAgreement;
use App\Models\Specific;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\SpecificAgreement; // o el nombre de tu modelo
use App\Services\CompanyService;
use App\Services\ContractService;
use Carbon\Carbon;

class SpecificController extends Controller
{

    protected $companyService;
    protected $contractService;

    public function __construct(ContractService $contractService, CompanyService $companyService)
    {
        $this->companyService = $companyService;
        // Inyectar el servicio de contratos
        $this->contractService = $contractService;
    }
   
    public function create()
    {
        

    }





    public function index()
    {
        //
    }

  


    public function store(StoreSpecificAgreementRequest $request)
    {
        // Validar los datos requeridos
        $validated = $request->validate([
            'empresa_razon_social' => 'required|string|max:255',
            'empresa_nominacion' => 'nullable|string|max:255',
            'empresa_calle' => 'nullable|string|max:255',
            'empresa_numero' => 'nullable|string|max:10',
            'empresa_ciudad' => 'nullable|string|max:100',
            'empresa_provincia' => 'nullable|string|max:100',
            'contact_nombre' => 'required|string|max:100',
            'contact_apellido' => 'required|string|max:100',
            'contact_email' => 'required|email',
            'contact_celular' => 'required|string|max:20',
            'contact_cargo' => 'required|string|max:100',
            'firma_nombre' => 'nullable|string',
            'firma_apellido' => 'nullable|string',
            'firma_dni' => 'nullable|string',
            'firma_email' => 'nullable|email',
            'firma_cargo' => 'nullable|string',
            'signing_place' => 'required|string',
            'signing_date' => 'required|date',
            'objetivo' => 'nullable|string',
            'compromisos' => 'nullable|string',
            'responsable_control_empresa' => 'nullable|string',
            'responsable_control_fio' => 'nullable|string',
            'descargar_convenio' => 'nullable|boolean',
        ]);

        // Crear convenio específico en la base de datos
        $convenio = Specific::create([
            'contract_id' => 1, // relacionarlo si ya existe
            'signing_date' => $validated['signing_date'],
            'objective' => $validated['objetivo'],
            'commitment_parties' => $validated['compromisos'],
            'responsable_control_company' => $validated['responsable_control_empresa'],
            'responsable_control_fio' => $validated['responsable_control_fio'],
        ]);

        // Crear archivo Word
        $template = new TemplateProcessor(storage_path('app/templates/convenio_especifico.docx'));

        $template->setValue('razon_social', $validated['empresa_razon_social']);
        $template->setValue('nominacion', $validated['empresa_nominacion']);
        $template->setValue('calle', $validated['empresa_calle']);
        $template->setValue('nro', $validated['empresa_numero']);
        $template->setValue('ciudad', $validated['empresa_ciudad']);
        $template->setValue('provincia', $validated['empresa_provincia']);

        $template->setValue('nombre_rep_contacto', $validated['contact_nombre'] . ' ' . $validated['contact_apellido']);
        $template->setValue('cargo_rep_contacto', $validated['contact_cargo']);
        $template->setValue('cuil_rep_contacto', $validated['contact_celular']);
        $template->setValue('email_rep_contacto', $validated['contact_email']);

        $template->setValue('nombre_rep_firma', $validated['firma_nombre'] . ' ' . $validated['firma_apellido']);
        $template->setValue('dni_rep_firma', $validated['firma_dni']);
        $template->setValue('email_rep_firma', $validated['firma_email']);
        $template->setValue('cargo_rep_firma', $validated['firma_cargo']);

        $template->setValue('lugar_firma', $validated['signing_place']);
        $template->setValue('dia', Carbon::parse($validated['signing_date'])->format('d'));
        $template->setValue('mes', Carbon::parse($validated['signing_date'])->translatedFormat('F'));
        $template->setValue('anio', Carbon::parse($validated['signing_date'])->format('Y'));

        $template->setValue('objetivo', $validated['objetivo']);
        $template->setValue('compromisos', $validated['compromisos']);
        $template->setValue('responsable_empresa', $validated['responsable_control_empresa']);
        $template->setValue('responsable_fio', $validated['responsable_control_fio']);

        // Guardar archivo generado
        $fileName = 'convenio_especifico_' . $convenio->id . '.docx';
        $savePath = storage_path("app/convenios_generados/especificos/$fileName");
        $template->saveAs($savePath);

        // Subir el archivo a la BD (opcional)
        $convenio->file = file_get_contents($savePath);
        $convenio->save();

        // Establecer estado "En Departamento" (si tenés un modelo de estados)
        $convenio->status = 'en_departamento';
        $convenio->save();

        // Opción de descargar
        if ($validated['descargar_convenio']) {
            return response()->download($savePath);
        }

        // Redirigir al main
        return redirect()->route('specific-agreement.index')->with('success', 'Convenio Específico creado correctamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
