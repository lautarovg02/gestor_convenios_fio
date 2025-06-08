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
        // Representante Contacto
        'contact_nombre'   => 'required|string|max:255',
        'contact_apellido' => 'required|string|max:255',
        'contact_celular'  => 'required|string|max:30',
        'contact_email'    => 'required|email|max:255',
        'contact_empresa'  => 'required|string|max:255',
        'contact_cargo'    => 'nullable|string|max:255',
        
        'razon_social'     => 'required|string|max:255',
        'cuit'             => 'required|string|max:20',
        'domicilio'        => 'required|string|max:255',
        'localidad'        => 'required|string|max:255',
        'provincia'        => 'required|string|max:255',
        'firma_nombre'     => 'required|string|max:255',
        'firma_apellido'   => 'required|string|max:255',
        'firma_dni'        => 'required|string|max:20',
        'firma_cargo'      => 'required|string|max:20',
        'entidad'          => 'required|string|max:255',
        'rubro'            => 'required|string|max:255',
        'dedicacion'       => 'required|string|max:255',
    ];
}

}
