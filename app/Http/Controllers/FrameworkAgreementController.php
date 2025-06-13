<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvenioMarcoRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyEntity;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\Employee;
use App\Models\FrameworkAgreement;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Psy\Readline\Hoa\Console;
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
        $templateProcessor->setValue('cuil', safe($validated['cuil_prefijo']) . '-' . safe($validated['cuil_dni']) . '-' . safe($validated['cuil_dv']));
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
       
        function numeroATexto($numero) {
        $mapa = [
            '20' => 'veinte',
            '21' => 'veintiuno',
            '22' => 'veintidós',
            '23' => 'veintitrés',
            '24' => 'veinticuatro',
            '25' => 'veinticinco',
            '26' => 'veintiséis',
            '27' => 'veintisiete',
            '28' => 'veintiocho',
            '29' => 'veintinueve',
            '30' => 'treinta',
        ];
        return $mapa[$numero] ?? $numero;
    }
        $fecha = $_POST['fecha_firma'];
        $anioTexto = numeroATexto(date('y', strtotime($fecha))); 
        
        $templateProcessor->setValue('anio', $anioTexto);

        $relativePath = 'convenios_generados/' . date('Y/m'); // Ej: 'convenios_generados/2025/06'
        Storage::makeDirectory($relativePath); // Esto crea la carpeta si no existe

        $nombreArchivo = 'convenio_marco_' . Str::slug($validated['razon_social']) . '.docx';
        $fullPath = storage_path('app/' . $relativePath . '/' . $nombreArchivo);

        $templateProcessor->saveAs($fullPath);
        /*GUARDAR CONVENIO MARCO EN LA BD*/ 
        $convenioMarco = new Contract();
        $convenioMarco->signing_date = $validated['fecha_firma'];
        
        
        $cuit = $validated['cuit_prefijo'] . $validated['cuit_dni'] . $validated['cuit_dv'];

        $company = Company::where('cuit', $cuit)->first();
        if($company){
               $convenioMarco->company_id = $company->id;
        }
        else{
             // Manejar entidad (entity)
        $entitySelected = $request->entidad === 'other' ? $request->other_entity_input : $request->entidad;
        $entity = CompanyEntity::firstOrCreate(['name' => $entitySelected]);
        $city = City::where('name',$request->localidad)->first(); //debe existir la ciudad
       

        // Crear la empresa
        $newCompany = Company::create([
            'denomination' => $request->razon_social,
            'company_name' => $request->razon_social,
            'cuit' => $request->cuit,
            'city_id' => $city->id,
            'entity_id' => $entity->id
        ]);
        
        $convenioMarco->company_id =$newCompany->id;
        }
        

         // Obtener una secretaria random
        $randomSecretary = Secretary::inRandomOrder()->first();
        $convenioMarco->secretary_id = $randomSecretary->id;
       

        // Obtener un docente random
         $randomTeacher = Teacher::inRandomOrder()->first();
         $convenioMarco->teacher_id = $randomTeacher->id;

        // Crear o obtener los empleados de contacto y representante

        $cuil = $validated['cuil_prefijo'] . $validated['cuil_dni'] . $validated['cuil_dv'];
        $employee = Employee::where('cuil', $cuil)->first();
        if($employee){
               $convenioMarco->contact_employee_id = $employee->id;
        }
        else{
        $contact_employee = Employee::create ([
            'name' => $validated['contact_nombre'],
            'lastname' => $validated['contact_apellido'],
            'dni' => $validated['contact_dni'],
            'cuil' => $cuil,
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_celular'],
            'position' => $validated['contact_cargo'],
            'is_represent' => true,
            'company_id' => $newCompany->id,
        ]);
        $convenioMarco->contact_employee_id = $contact_employee->id;
        }

        $dni = $validated['firma_dni'];
        $employeeFirma = Employee::where('dni', $dni)->first();
        if($employeeFirma){
               $convenioMarco->representative_employee_id = $employeeFirma->id;
        }
        else{
        $representative_employee = Employee::create([
            'name' => $validated['firma_nombre'],
            'lastname' => $validated['firma_apellido'],
            'dni' => $validated['firma_dni'],
            'cuil' => null,
            'email' => $validated['firma_email'] ?? null,
            'phone' => $validated['firma_celular'] ?? null,
            'position' => $validated['firma_cargo'],
            'is_represent' => true,
            'company_id' => $newCompany->id,
        ]);
        $convenioMarco->representative_employee_id = $representative_employee->id;
        }   
        
        // Obtener un rector random
         $randomRector = Teacher::where('is_rector', true)->inRandomOrder()->first();
         $convenioMarco->rector = $randomRector->id;
        
       
        //Crear estado
         $status = ContractStatus::create([
            'status' => 'SEVyT',
            'time_limit' => 48  //chequear time no acepta 48hs me parece
        ]);
        $convenioMarco->contract_status_id = $status->id;        

        // Crear o encontrar el tipo de convenio marco
        $type = TypeFrameworkAgreement::create([
            'type' => 'Convenio Marco'
        ]);
        $convenioMarco->type_framework_agreement_id = $type->id;

        $convenioMarco->file = $fullPath;

        $fecha = $validated['fecha_firma'];
        $convenioMarco->creation_date = $fecha;

        $convenioMarco->save();

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
