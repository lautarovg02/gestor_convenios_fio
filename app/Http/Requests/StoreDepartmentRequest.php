<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
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
            'name' => ['required','string','max:200', Rule::unique('departments')->ignore($this->department)],
            'director_id' => 'required|exists:teachers,id|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'La denominación es un campo obligatorio.',
            'name.max' => 'La cantidad máxima de caracteres es de :max',
            'director_id.required' => 'El director es  un campo obligatorio.',
            'director_id.exists' => 'El  director seleccionado no es válido.',
            'name.unique' => 'Ya existe un departamento con ese nombre. Por favor, elige otro nombre.',

        ];
    }
}


