<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpecificResidenceAgreement extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajusta esto según tus requisitos de autorización.
    }

    /**
     * Get the validation rules that apply to the request.
     */

    protected function prepareForValidation()
{
    $studentCuil = $this->input('student_cuil_prefijo') .
                   $this->input('student_cuil_dni') .
                   $this->input('student_cuil_dv');

    $this->merge([
        'studentCuil' => $studentCuil
    ]);

    
}


    public function rules(): array
    {

                $studentDni = $this->input('dniStudent');
        return [
            'companyName' => 'required|string|max:255',
            'agreementName' => 'required|string|max:255',
            'contract_id' => 'required',
            'tasks' => 'required|string',
            'fecha_firma' => 'nullable|date',
            'studentName' => 'required|string|max:255',
            'studentLastName' => 'required|string|max:255',
            'dniStudent' => 'required|string|numeric|unique:students,dni,' . $studentDni,
            'studentCuil' => [
                                'nullable',
                                'string',
                                'size:11',
                                Rule::unique('students', 'cuil')->ignore($studentDni, 'dni')
                                ],
    
            'studentEmail' => [
                                'required',
                                'email',
                                Rule::unique('students', 'email')->ignore($studentDni, 'dni')
                                ],    
            'studentCelular' => [
                                'required',
                                'String',
                                Rule::unique('students', 'phone_numb')->ignore($studentDni, 'dni')
                                ], 
            'studentCarrer' => 'required|string|max:255',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'companyName.required' => 'La Empresa es obligatoria.',
            'agreementName.required' => 'El nombre del acuerdo es obligatorio.',
            'tasks.required' => 'Debe completar este campo.',
            'fecha_firma.date' => 'La fecha de firma no tiene un formato válido.',
            'studentName.required' => 'El nombre del estudiante es obligatorio.',
            'studentLastName.required' => 'El apellido" del estudiante es obligatorio.',
            'dniStudent.required' => 'El campo DNI del estudiante es obligatorio.',
            'studentEmail.required' => 'El email es obligatorio.',
            'contactEmail.unique' => 'El correo electrónico del contacto ya existe en otro estudiante.',
            'studentEmail.email' => 'El email debe ser un correo válido.',
            'studentCuil.size' => 'El CUIL del estudiante debe tener 11 dígitos.',
            'studentCelular.required' => 'El celular es obligatorio.',
            'studentCelular.unique' => 'El número de celular ya está registrado en otro estudiante.',
            'studentCarrer.required' => 'La carrera es obligatoria.',
        ];
    }
}