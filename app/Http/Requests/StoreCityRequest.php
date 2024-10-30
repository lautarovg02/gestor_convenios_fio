<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
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
            'name' => 'required|string|max:40',
            'province_id' => 'required|integer',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'La ciudad es un campo obligatorio.',
            'name.max' => 'La máxima cantidad de caracteres permitida es de 40',
            'province_id.required' => 'La provincia es un campo obligatorio.'
        ];
    }
}
