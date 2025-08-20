<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecificAgreementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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

            'objetivo' => 'required|string',
            'compromisos' => 'required|string|max:255',

            'titular' => 'nullable|string|max:255',
            'confidencialidad' => 'nullable|in:si,no',

            'responsable_nombre' => 'nullable|string|max:255',
            'responsable_apellido' => 'nullable|string|max:255',
            'responsable_cargo' => 'nullable|string|max:255',

            'file' => 'nullable|file|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'contract_id.required' => 'Se debe seleccionar una empresa vinculada a un convenio marco.',
            'contract_id.exists' => 'El contrato seleccionado no es válido.',

            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.max' => 'La razón social no puede superar los 255 caracteres.',

            'empresa_calle.max' => 'La calle de la empresa no puede superar los 255 caracteres.',
            'empresa_numero.max' => 'El número de la empresa no puede superar los 20 caracteres.',
            'empresa_ciudad.required' => 'La ciudad de la empresa es obligatoria.',
            'empresa_ciudad.max' => 'La ciudad no puede superar los 100 caracteres.',
            'provincia.required' => 'La provincia es obligatoria.',
            'provincia.max' => 'La provincia no puede superar los 100 caracteres.',
            'pais.max' => 'El país no puede superar los 100 caracteres.',

            'contact_nombre.required' => 'El nombre de contacto es obligatorio.',
            'contact_apellido.required' => 'El apellido de contacto es obligatorio.',
            'contact_email.required' => 'El email de contacto es obligatorio.',
            'contact_email.email' => 'El email de contacto debe ser válido.',
            'contact_celular.required' => 'El celular de contacto es obligatorio.',
            'contact_celular.max' => 'El celular no puede superar los 20 caracteres.',
            'contact_cargo.required' => 'El cargo del contacto es obligatorio.',

            'firma_dni.numeric' => 'El DNI de la firma debe ser numérico.',
            'firma_email.email' => 'El email de la firma debe ser válido.',

            'lugar_firma.required' => 'El lugar de firma es obligatorio.',
            'fecha_firma.required' => 'La fecha de firma es obligatoria.',
            'fecha_firma.date' => 'La fecha de firma debe tener un formato válido.',

            'student_id.required' => 'El estudiante es obligatorio.',
            'student_id.exists' => 'El estudiante seleccionado no es válido.',

            'becario.required' => 'El nombre del becario es obligatorio.',

            'objetivo.required' => 'El objetivo es obligatorio.',

            'confidencialidad.in' => 'El campo confidencialidad solo puede ser "si" o "no".',

            'file.file' => 'El archivo debe ser válido.',
            'file.max' => 'El archivo no debe superar los 10 MB.',

            'compromisos.required' => 'Complete los compromisos de la empresa',
        ];
    }
}
