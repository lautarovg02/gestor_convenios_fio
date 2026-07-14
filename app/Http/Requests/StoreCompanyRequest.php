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
                'company_name' => 'required|string|max:100',
                'sector' => 'required|string|max:40',
                'entity' => 'required|string',
                'other_entity_input' => 'nullable|required_if:entity,other|string|max:40',
                'company_category' => 'required|string|max:255',
                'confidentiality' => 'required|boolean',
                'scope' => 'required|string',
                'street' => 'required|string|max:100',
                'number' => 'required|integer',
                'rubro' => 'required|string|max:255',
                'dedicacion' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'denomination.required' => 'La razón social es  un campo obligatorio.',
            'denomination.max' => 'La cantidad máxima de caracteres es de :max',
            'cuit.required' => 'El CUIT es un campo obligatorio.',
            'cuit.digits' => 'El CUIT debe tener exactamente 11 dígitos.',
            'cuit.unique' => 'El cuit ya existe en la base de datos.',
            'city_id.required' => 'La ciudad  es  un campo obligatorio.',
            'city_id.exists' => 'La ciudad seleccionada no es válida.',
            'other_entity_input' => 'Debe especificar una entidad si selecciona "Otro tipo".',
            'company_name.required' => 'El nombre de la empresa es un campo obligatorio.',
            'sector.required' => 'El sector es un campo obligatorio.',
            'entity.required' => 'La entidad es un campo obligatorio.',
            'company_category.required' => 'La categoría es un campo obligatorio.',
            'confidentiality.required' => 'La cláusula de confidencialidad es un campo obligatorio.',
            'scope.required' => 'El ámbito es un campo obligatorio.',
            'street.required' => 'La calle es un campo obligatorio.',
            'number.required' => 'El número es un campo obligatorio.',
            'rubro.required' => 'El rubro es un campo obligatorio.',
            'dedicacion.required' => 'La dedicación es un campo obligatorio.',

        ];
    }
}
