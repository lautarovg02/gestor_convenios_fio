<?php

namespace App\Http\Requests;

use App\Enums\EntityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @dairagalceran
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
                'denomination' => 'required|string|max:40',
                'cuit' => 'required|Integer|digits:11', //|unique:companies,cuit',
                'city_id' => 'required|exists:cities,id|integer',
                'company_name' => 'nullable|string|max:100',
                'sector' => 'nullable|string|max:40',
                'entity_id' => ['nullable', 'in:' . implode(',', EntityType::values()) . ',other'],
                'other_entity_input' => 'nullable|required_if:entity,other|string|max:40',
                'company_category' => 'nullable|string|max:20',
                'scope' => 'nullable|string ',
                'street' => 'nullable|string|max:40',
                'number' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'denomination.required' => 'La denominación es  un campo obligatorio.',
            'denomination.max' => 'La cantidad máxima de caracteres es de :max',
            'cuit.required' => 'El CUIT es un campo obligatorio.',
            'cuit.digits' => 'El CUIT debe tener exactamente 11 dígitos.',
            'cuit.unique' => 'El cuit ya existe en la base de datos.',
            'city_id.required' => 'La ciudad  es  un campo obligatorio.',
            'city_id.exists' => 'La ciudad seleccionada no es válida.',
            'other_entity_input' => 'Debe especificar una entidad si selecciona "Otro tipo".'
        ];
    }
}
