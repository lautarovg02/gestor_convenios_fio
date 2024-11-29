<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
     * @lautarovg02
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'dni' => 'required|integer|digits:8',
            'cuil' => [
                'nullable',
                'integer',
                'digits:11',
                function ($attribute, $value, $fail) {
                    $dni = $this->input('dni');
                    if (!str_contains($value, $dni) || strpos($value, $dni) !== 2) {
                        $fail('El CUIL debe contener el DNI en la posición correspondiente.');
                    }
                },
            ],
            'is_rector' => 'required|boolean',
            'is_dean' => 'required|boolean',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     * @lautarovg02
     */
    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'El nombre es un campo obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto válida.',

            // Mensajes para el campo "lastname"
            'lastname.required' => 'El apellido es un campo obligatorio.',
            'lastname.string' => 'El apellido debe ser una cadena de texto válida.',

            // Mensajes para el campo "dni"
            'dni.required' => 'El DNI es un campo obligatorio.',
            'dni.integer' => 'El DNI debe ser un número entero.',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',

            // Mensajes para el campo "cuil"
            'cuil.required' => 'El CUIL es un campo obligatorio.',
            'cuil.integer' => 'El CUIL debe ser un número entero.',
            'cuil.digits' => 'El CUIL debe tener exactamente 11 dígitos.',
            'cuil.custom' => 'El CUIL debe contener el DNI en la posición correspondiente.',
        ];
    }
}
