<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCareerRequest extends FormRequest
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
     * @dairagalceran
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // Campo 'name' no debe estar vacío
            'coordinator_id' => 'required|exists:teachers,id', // Campo 'coordinator_id' debe seleccionarse
            'department_id' => 'required|exists:departments,id', // Campo 'department_id' debe seleccionarse
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     * @dairagalceran
     */

    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'Ya existe una carrera con el mismo nombre.',
            'name.max' => 'La cantidad máxima de caracteres  es de :max',
        ];
    }
}
