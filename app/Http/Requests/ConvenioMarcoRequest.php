<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConvenioMarcoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
        $contact_dni = $this->input('contact_dni');
        $firma_dni = $this->input('firma_dni');

    return [
       // Representante contacto
            'contact_nombre' => ['required', 'string', 'max:255'],
            'contact_apellido' => ['required', 'string', 'max:255'],
            'cuil_prefijo' => ['required', 'numeric'],
            'cuil_dni' => ['required', 'numeric'],
            'cuil_dv' => ['required', 'numeric'],
            'contact_dni' => ['required', 'numeric'],
            'contact_celular' => ['required', 'numeric'],
            'contact_email' => [
                                'required',
                                'email',
                                Rule::unique('employees', 'email')->ignore($contact_dni, 'dni')
                                ],  
            'contact_empresa' => ['required', 'string'],
            'contact_cargo' => ['nullable', 'string'],

            // Contraparte
            'razon_social' => ['required', 'string', 'max:255', 'unique:companies,company_name'],
            'ambito' => ['in:nacional,internacional', 'nullable'],
            'cuit_prefijo' => ['required', 'numeric'],
            'cuit_dni' => ['required', 'numeric'],
            'cuit_dv' => ['required', 'numeric'],
            'rubro' => ['nullable', 'string'],
            'entidad' => ['nullable', 'string'],
            'dedicacion' => ['nullable', 'string'],
            'titular' => ['nullable', 'string'],
            'confidencialidad' => ['nullable', 'in:si,no'],

            // Dirección
            'calle' => ['nullable', 'string', 'max:255'],
            'nro_calle' => ['nullable', 'string', 'max:20'],
            'codigo_postal' => ['nullable', 'numeric'],
            'localidad' => ['nullable', 'string'],
            'provincia' => ['nullable', 'string'],
            'pais' => ['nullable', 'string'],

            // Representante firma
            'firma_nombre' => ['nullable', 'string'],
            'firma_apellido' => ['nullable', 'string'],
            'firma_dni' => ['nullable', 'numeric'],
            'firma_cargo' => ['nullable', 'string'],
            'firma_email' => [
                                'required',
                                'email',
                                Rule::unique('employees', 'email')->ignore($firma_dni, 'dni')
                                ], 
            'firma_empresa_razon_social' => ['nullable', 'string'],

            // Lugar y fecha
            'lugar_firma' => ['nullable', 'string'],
            'fecha_firma' => ['nullable', 'date'],

            // Documentos
            'doc_afip' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
            'doc_estatuto' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
            'doc_autoridades' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png'],
    ];
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
            'contact_celular.required' => 'El número de celular del contacto es obligatorio.',
            'contact_email.required' => 'El correo electrónico del contacto es obligatorio.',
            'contact_email.email' => 'El correo electrónico del contacto no es válido.',
            'contact_email.unique' => 'El correo electrónico del contacto ya existe en otro usuario.',
            'contact_empresa.required' => 'La empresa del contacto es obligatoria.',
            'contact_cargo.required' => 'El cargo del contacto es obligatorio.',

            // Contraparte
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.unique' => 'Ya existe una empresa con esa razón social. Por favor, ingresá una diferente.',
            'ambito.required' => 'El ámbito del convenio es obligatorio.',
            'ambito.in' => 'El ámbito debe ser "nacional" o "internacional".',
            'contraparte_cuit.digits' => 'El CUIT debe tener 11 dígitos.',
            'contraparte_cuit.unique' => 'El CUIT ya está registrado.',
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
            'fecha_firma.date' => 'La fecha de la firma no es válida.'
    ];
}
}
