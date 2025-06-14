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
        'razon_social.unique' => 'Ya existe una empresa con esa razón social. Por favor, ingresá una diferente.',
    ];
}
}
