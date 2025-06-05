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
        return false;
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
        
    ];
}

}
