<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
        
        $companyId = $this->route('company')->id;

        return [
            'denomination' => 'required|string|max:40',
            'cuit' => ['required', 'string', 'digits:11', Rule::unique('companies', 'cuit')->ignore($companyId)],
            'city_id' => 'required|exists:cities,id',
            'company_name' => 'nullable|string|max:100',
            'sector' => 'required|string|max:40',
            'entity_id' => 'required|exists:company_entities,id',
            'company_category' => 'required|string|max:20',
            'scope' => 'required|string',
            'street' => 'required|string|max:40',
            'number' => 'required|integer',
            
            
            'afip_certificate' => 'nullable|file|mimes:pdf|max:2048', 
            'statute_confirmation' => 'nullable|file|mimes:pdf|max:2048',
            'authorities_assignment' => 'nullable|file|mimes:pdf|max:2048',
            'has_confidentiality_clause' => 'nullable|boolean',
           'confidentiality_clause_file' => 'nullable|file|mimes:pdf|max:2048',
        ];
    }
    
    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'denomination.required' => 'La razón social es un campo obligatorio.',
            'denomination.max' => 'La cantidad máxima de caracteres es de :max',
            'cuit.required' => 'El CUIT es un campo obligatorio.',
            'cuit.digits' => 'El CUIT debe tener exactamente 11 dígitos.',
            'cuit.unique' => 'El cuit ya existe en la base de datos.',
            'city_id.required' => 'La ciudad es un campo obligatorio.',
            'city_id.exists' => 'La ciudad seleccionada no es válida.',
            'company_name.string' => 'El nombre de la empresa debe ser una cadena de texto.',
            'company_name.max' => 'La cantidad máxima de caracteres es de :max',
            'sector.required' => 'El sector es un campo obligatorio.',
            'sector.max' => 'La cantidad máxima de caracteres es de :max',
            'entity_id.required' => 'El tipo de entidad es un campo obligatorio.',
            'entity_id.exists' => 'El tipo de entidad seleccionado no es válido.',
            'company_category.required' => 'La categoría de la empresa es un campo obligatorio.',
            'company_category.max' => 'La cantidad máxima de caracteres es de :max',
            'scope.required' => 'El ámbito es un campo obligatorio.',
            'street.required' => 'La calle es un campo obligatorio.',
            'street.max' => 'La cantidad máxima de caracteres es de :max',
            'number.required' => 'El número es un campo obligatorio.',
            'number.integer' => 'El número debe ser un valor entero.',
            'afip_certificate.file' => 'El certificado AFIP debe ser un archivo.',
            'afip_certificate.mimes' => 'El certificado AFIP debe ser un archivo PDF.',
            'afip_certificate.max' => 'El tamaño máximo del certificado AFIP es de :max kilobytes.',
            'statute_confirmation.file' => 'La confirmación de estatutos debe ser un archivo.',
            'statute_confirmation.mimes' => 'La confirmación de estatutos debe ser un archivo PDF.',
            'statute_confirmation.max' => 'El tamaño máximo de la confirmación de estatutos es de :max kilobytes.',
            'authorities_assignment.file' => 'La designación de autoridades debe ser un archivo.',
            'authorities_assignment.mimes' => 'La designación de autoridades debe ser un archivo PDF.',
            'authorities_assignment.max' => 'El tamaño máximo de la designación de autoridades es de :max kilobytes.',
            'confidentiality_clause_file.file' => 'El archivo de la cláusula de confidencialidad debe ser un archivo.',
            'confidentiality_clause_file.mimes' => 'El archivo de la cláusula de confidencialidad debe ser un archivo PDF.',
            'confidentiality_clause_file.max' => 'El tamaño máximo del archivo de la cláusula de confidencialidad es de :max kilobytes.',
        ];
    }
}