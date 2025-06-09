<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    return [
       // Representante contacto
            'contact_nombre' => ['required', 'string', 'max:255'],
            'contact_apellido' => ['required', 'string', 'max:255'],
            'cuit_prefijo' => ['required', 'numeric'],
            'cuit_dni' => ['required', 'numeric'],
            'cuit_dv' => ['required', 'numeric'],
            'contact_celular' => ['required', 'numeric'],
            'contact_email' => ['required', 'email'],
            'contact_empresa' => ['required', 'string'],
            'contact_cargo' => ['nullable', 'string'],

            // Contraparte
            'razon_social' => ['string', 'nullable'],
            'ambito' => ['in:nacional,internacional', 'nullable'],
            'cuit' => ['nullable', 'numeric'],
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
            'firma_mail' => ['nullable', 'email'],
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

}
