<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvenioMarcoRequest;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class FrameworkAgreementController extends Controller
{
       public function index()
    {
        return view("frameworkAgreement.create"); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $type = $request->query('type'); // lee ?type=marco

        return view("frameworkAgreement.create_$type"); // ej: agreements.create_marco
    
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{

        $validated = app(ConvenioMarcoRequest::class)->validated();
        // guardar usando $validated...

         // 3. Cargar plantilla Word desde storage
        $templatePath = storage_path('app/plantillas/Convenio Marco.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('razon_social', $validated['razon_social']);
        $templateProcessor->setValue('cuit', $validated['cuit']);
        $templateProcessor->setValue('domicilio', $validated['domicilio']);
        $templateProcessor->setValue('localidad', $validated['localidad']);
        $templateProcessor->setValue('provincia', $validated['provincia']);
        $templateProcessor->setValue('firma_nombre', $validated['firma_nombre']);
        $templateProcessor->setValue('firma_apellido', $validated['firma_apellido']);
        $templateProcessor->setValue('firma_dni', $validated['firma_dni']);
        $templateProcessor->setValue('firma_cargo', $validated['firma_cargo']);
        $templateProcessor->setValue('entidad', $validated['entidad']);
        $templateProcessor->setValue('rubro', $validated['rubro']);
        $templateProcessor->setValue('dedicacion', $validated['dedicacion']);

         // 5. Guardar nuevo archivo Word en una carpeta
        $razon_social = $validated['razon_social'];
        $nombreArchivo = "convenio_marco_{$razon_social}.docx";
        $rutaSalida = storage_path('app/convenios_generados/' . $nombreArchivo);
        $templateProcessor->saveAs($rutaSalida);

        return response()->download($rutaSalida);

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
