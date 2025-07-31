<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecificAgreementRequest extends FormRequest
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
            'contract_id' => 'required|exists:contracts,id',
            'razon_social' => 'required|string|max:255',
            'empresa_calle' => 'nullable|string|max:255',
            'empresa_numero' => 'nullable|string|max:20',
            'empresa_ciudad' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'pais' => 'nullable|string|max:100',

            'contact_nombre' => 'required|string|max:255',
            'contact_apellido' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_celular' => 'required|string|max:20',
            'contact_cargo' => 'required|string|max:100',

            'firma_nombre' => 'nullable|string|max:255',
            'firma_apellido' => 'nullable|string|max:255',
            'firma_dni' => 'nullable|numeric',
            'firma_email' => 'nullable|email',
            'firma_cargo' => 'nullable|string|max:100',

            'lugar_firma' => 'required|string|max:255',
            'fecha_firma' => 'required|date',

            'responsable_control_fio' => 'nullable|string|max:255',
            'responsable_control_company' => 'nullable|string|max:255',

            
            
            'student_id' => ['required', 'exists:students,id'],
            'becario' => ['required', 'string'],


            'objetivo' => 'nullable|string',
            'compromisos' => 'nullable|string',

            'titular' => 'nullable|string|max:255',
            'confidencialidad' => 'nullable|in:si,no',

            'responsable_nombre' => 'nullable|string|max:255',
            'responsable_apellido' => 'nullable|string|max:255',
            'responsable_cargo' => 'nullable|string|max:255',

            'file' => 'nullable|file|max:10240', // máx. 10 MB

        ];
    }
}
