<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFrameworkInternshipAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FrameworkInternshipAgreementController extends Controller
{/*
       public function index()
    {
        return view('frameworkInternshipAgreement.index'); 
    }
*/

    public function create()
    {

        return view("frameworkInternshipAgreement.create");
    }



    public function store(StoreFrameworkInternshipAgreement $request)
    {
        $validated = $request->validated();

        function safe($value)
        {
            return $value ?? '______';
        }

        // 3. Cargar plantilla Word desde storage
        $templatePath = storage_path('app/plantillas/Convenio_Marco_de_Pasantia.docx');
        $templateProcessor = new TemplateProcessor($templatePath);



        // Seteo de valores en el template
        $templateProcessor->setValue('razon_social', safe($validated['razon_social']));
        $templateProcessor->setValue('calle', safe($validated['calle']));
        $templateProcessor->setValue('nro_calle', safe($validated['nro_calle']));
        $templateProcessor->setValue('ciudad', safe($validated['localidad']));
        $templateProcessor->setValue('nombre_rep_contacto', safe($validated['contact_nombre']) . ' ' . safe($validated['contact_apellido']));
        $templateProcessor->setValue('cargo_rep_contacto', safe($validated['contact_cargo']));
        $templateProcessor->setValue('cuil_rep_contacto', safe($validated['cuil_prefijo']) . '-' . safe($validated['cuil_dni']) . '-' . safe($validated['cuil_dv']));
        $templateProcessor->setValue('nombre_rep_firma', safe($validated['firma_nombre']) . ' ' . safe($validated['firma_apellido']));
        $templateProcessor->setValue('cargo_rep_firma', safe($validated['firma_cargo']));
        $templateProcessor->setValue('rep_firma_empresa_razon_social', safe($validated['firma_empresa_razon_social']));
        $templateProcessor->setValue('dia', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->format('d') : '____');
        $templateProcessor->setValue('mes', !empty($validated['fecha_firma']) ? \Carbon\Carbon::parse($validated['fecha_firma'])->translatedFormat('F') : '____');


        $relativePath = 'convenios_generados/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Esto crea la carpeta si no existe

        $nombreArchivo = 'convenio_marco_pasantias_' . Str::slug($validated['razon_social']) . '.docx';
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
