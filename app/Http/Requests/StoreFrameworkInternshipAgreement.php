<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class StoreFrameworkInternshipAgreement extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {

        $contact_dni = $this->input('contact_dni');
        $firma_dni = $this->input('firma_dni');

        return [
            // Representante contacto
            'contact_nombre' => ['required', 'string', 'max:255'],
            'contact_apellido' => ['required', 'string', 'max:255'],
            'contact_dni' => ['required', 'numeric'],
            'contact_cuil' => ['nullable', 'numeric', 'digits:11'],
            'contact_celular' => ['required', 'numeric'],

            'contact_email' => [
                                'required',
                                'email',
                                Rule::unique('employees', 'email')->ignore($contact_dni, 'dni')
                                ],                 
            'contact_empresa' => ['required', 'string'],
            'contact_cargo' => ['required', 'string'],
            'contact_cuil' => ['required', 'numeric'],
            // Contraparte
            'razon_social' => ['required', 'string'],
            'ambito' => ['required', 'in:nacional,internacional'],
            'contraparte_cuit' => ['required', 'nullable', 'numeric', 'digits:11'],
            'contraparte_rubro' => ['required', 'string'],
            'titular' => ['required', 'string'],
            'confidencialidad' => ['required'],
            'company_id' => [
                'required', 
                'exists:companies,id',
                function ($attribute, $value, $fail) {
                    $hasActive = \App\Models\Contract::where('company_id', $value)
                        ->where('type_framework_agreement_id', 1)
                        ->whereHas('status', function($q) {
                            $q->whereNotIn('status', ['Deshabilitado', 'Finalizado']);
                        })->exists();
                    if ($hasActive) {
                        $fail('La empresa ya posee un Convenio Marco de Pasantía activo.');
                    }
                },
            ],

            // Dirección
            'calle' => ['required', 'string', 'max:255'],
            'nro_calle' => ['required', 'string', 'max:20'],
            'codigo_postal' => ['required', 'numeric'],
            'localidad' => ['required', 'string'],
            'provincia' => ['required', 'string'],
            'pais' => ['required', 'string'],

            // Representante firma
            'firma_nombre' => ['required', 'string'],
            'firma_apellido' => ['required', 'string'],
            'firma_dni' => ['required', 'numeric'],
            'firma_cargo' => ['required', 'string'],
            'firma_email' => [
                                'required',
                                'email',
                                Rule::unique('employees', 'email')->ignore($firma_dni, 'dni')
                                ], 

            'firma_empresa_razon_social' => ['required', 'string'],

            // Lugar y fecha
            'lugar_firma' => ['required', 'string'],
            'fecha_firma' => ['required', 'date'],

            // Responsables institucionales
            'teacher_id'   => ['required', 'exists:teachers,id'],
            'rector_id'    => ['required', 'exists:teachers,id'],
            'secretary_id' => ['required', 'exists:secretaries,id'],
        ];
    }


public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $dniContacto = $this->input('contact_dni');
        $dniFirma = $this->input('firma_dni');
        
        $empresaFormularioContacto = $this->input('contact_empresa');
        $empresaFormularioFirma = $this->input('firma_empresa_razon_social');
        $razonSocialFormulario = $this->input('razon_social');

        // Validar que ambas empresas coincidan con la razón social de la contraparte
        if ($empresaFormularioContacto !== $razonSocialFormulario) {
            $validator->errors()->add('contact_empresa', 'La empresa del representante de contacto debe coincidir con la razón social de la contraparte.');
        }

        if ($empresaFormularioFirma !== $razonSocialFormulario) {
            $validator->errors()->add('firma_empresa_razon_social', 'La empresa del representante firmante debe coincidir con la razón social de la contraparte.');
        }

        // Validar que el empleado de contacto no esté en otra empresa
        $empleadoContacto = Employee::where('dni', $dniContacto)->first();

        if ($empleadoContacto) {
            $empresaExistente = Company::find($empleadoContacto->company_id);

            if ($empresaExistente && $empresaExistente->denomination !== $empresaFormularioContacto) {
                $validator->errors()->add('contact_empresa', 'El empleado ya existe y está asignado a otra empresa.');
            }
        }

        // Validar que el empleado firmante no esté en otra empresa
        $empleadoFirma = Employee::where('dni', $dniFirma)->first();

        if ($empleadoFirma) {
            $empresaExistenteFirma = Company::find($empleadoFirma->company_id);

            if ($empresaExistenteFirma && $empresaExistenteFirma->denomination !== $empresaFormularioFirma) {
                $validator->errors()->add('firma_empresa_razon_social', 'El firmante ya existe y está asignado a otra empresa.');
            }
        }
    });
}



    public function messages(): array
    {
        return [
            // Representante contacto
            'contact_nombre.required' => 'El nombre del representante de contacto es obligatorio.',
            'contact_apellido.required' => 'El apellido del representante de contacto es obligatorio.',
            'contact_dni.required' => 'El DNI del contacto es obligatorio.',
            'contact_dni.unique' => 'El DNI del contacto ya está registrado.',
            'contact_cuil.digits' => 'El CUIL debe tener 11 dígitos.',
            'contact_cuil.unique' => 'El CUIL ya está registrado.',
            'contact_cuil.required' => 'El CUIL del contacto es obligatorio.',
            'contact_celular.required' => 'El número de celular del contacto es obligatorio.',
            'contact_email.required' => 'El correo electrónico del contacto es obligatorio.',
            'contact_email.email' => 'El correo electrónico del contacto no es válido.',
            'contact_email.unique' => 'El correo electrónico del contacto ya existe en otro usuario.',
            'contact_empresa.required' => 'La empresa del contacto es obligatoria.',
            'contact_cargo.required' => 'El cargo del contacto es obligatorio.',

            // Contraparte
            'razon_social.required' => 'La razón social es obligatoria.',
            'ambito.required' => 'El ámbito del convenio es obligatorio.',
            'ambito.in' => 'El ámbito debe ser "nacional" o "internacional".',
            'contraparte_cuit.digits' => 'El CUIT debe tener 11 dígitos.',
            'contraparte_cuit.unique' => 'El CUIT ya está registrado.',
            'contraparte_cuit.required' => 'El CUIT de la contraparte es obligatorio.',
            'contraparte_rubro.required' => 'El rubro es obligatorio.',
            'titular.required' => 'Este campo es obligatorio.',
            'confidencialidad.required' => 'Debe indicar si existe un acuerdo de confidencialidad.',


            // Dirección
            'calle.required' => 'La calle es obligatoria.',
            'nro_calle.required' => 'El número de calle es obligatorio.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'localidad.required' => 'La ciudad es obligatoria.',
            'provincia.required' => 'La provincia es obligatoria.',
            'pais.required' => 'El país es obligatorio.',

            // Representante firma
            'firma_nombre.required' => 'El nombre del firmante es obligatorio.',
            'firma_apellido.required' => 'El apellido del firmante es obligatorio.',
            'firma_dni.required' => 'El DNI del firmante es obligatorio.',
            'firma_cargo.required' => 'El cargo del firmante es obligatorio.',
            'firma_email.required' => 'El correo electrónico del firmante es obligatorio.',
            'firma_email.unique' => 'El correo electrónico del firmante ya existe en otro usuario.',
            'firma_empresa_razon_social.required' => 'La razón social de la empresa firmante es obligatoria.',

            // Lugar y fecha
            'lugar_firma.required' => 'El lugar de la firma es obligatorio.',
            'fecha_firma.required' => 'La fecha de la firma es obligatoria.',
            'fecha_firma.date' => 'La fecha de la firma no es válida.',

            // Responsables institucionales
            'teacher_id.required'   => 'Debe seleccionar un docente responsable.',
            'teacher_id.exists'     => 'El docente seleccionado no existe.',
            'rector_id.required'    => 'Debe seleccionar un rector.',
            'rector_id.exists'      => 'El rector seleccionado no existe.',
            'secretary_id.required' => 'Debe seleccionar una secretaria.',
            'secretary_id.exists'   => 'La secretaria seleccionada no existe.',
        ];
    }
}
