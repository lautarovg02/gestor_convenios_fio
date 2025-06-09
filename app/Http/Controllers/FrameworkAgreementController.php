<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvenioMarcoRequest;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Storage;
use Str;

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
         function safe($value)
        {
            return $value ?? '______';
        }

        // 3. Cargar plantilla Word desde storage
        $templatePath = storage_path('app/plantillas/Convenio Marco.docx');
        $templateProcessor = new TemplateProcessor($templatePath);



        // Seteo de valores en el template
        $templateProcessor->setValue('razon_social', safe($validated['razon_social']));
        $templateProcessor->setValue('calle', safe($validated['calle']));
        $templateProcessor->setValue('nro_calle', safe($validated['nro_calle']));
        $templateProcessor->setValue('ciudad', safe($validated['localidad']));
        $templateProcessor->setValue('provincia', safe($validated['provincia']));
        $templateProcessor->setValue('rubro', safe($validated['rubro']));
        $templateProcessor->setValue('entidad', safe($validated['entidad']));
        $templateProcessor->setValue('dedicacion', safe($validated['dedicacion']));
        $templateProcessor->setValue('nombre_rep_contacto', safe($validated['contact_nombre']) . ' ' . safe($validated['contact_apellido']));
        $templateProcessor->setValue('cargo_rep_contacto', safe($validated['contact_cargo']));
        $templateProcessor->setValue('cuit', safe($validated['cuit_prefijo']) . '-' . safe($validated['cuit_dni']) . '-' . safe($validated['cuit_dv']));
        $templateProcessor->setValue('nombre_rep_firma', safe($validated['firma_nombre']) . ' ' . safe($validated['firma_apellido']));
        $templateProcessor->setValue('cargo_rep_firma', safe($validated['firma_cargo']));
        $templateProcessor->setValue('firma_dni', safe($validated['firma_dni']));
        $templateProcessor->setValue('rep_firma_empresa_razon_social', safe($validated['firma_empresa_razon_social']));
        $templateProcessor->setValue('dia', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $templateProcessor->setValue('mes', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');


        $relativePath = 'convenios_generados/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Esto crea la carpeta si no existe

        $nombreArchivo = 'convenio_marco_' . Str::slug($validated['razon_social']) . '.docx';
        $fullPath = storage_path('app/' . $relativePath . '/' . $nombreArchivo);
        $templateProcessor->saveAs($fullPath);

        return response()->download($fullPath);
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
