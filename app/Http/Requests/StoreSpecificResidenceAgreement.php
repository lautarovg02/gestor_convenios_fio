<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Student;

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


    public function rules(): array
    {

        $studentDni = $this->input('dniStudent');
        return [
            'companyName' => 'required|string|max:255',
            'companyRepresentative' => 'required|string|max:255',
            'companyId' => 'required',
            'agreementName' => 'required|string|max:255',
            'contract_id' => [
                'required',
                'exists:contracts,id',
                function ($attribute, $value, $fail) {
                    $contract = \App\Models\Contract::find($value);
                    if ($contract && in_array($contract->status->status, ['Finalizado', 'Deshabilitado'])) {
                        $fail('El convenio marco seleccionado se encuentra Finalizado o Deshabilitado.');
                    }
                },
            ],
            'tasks' => 'required|string',
            'fecha_firma' => 'nullable|date',
            'fecha_inicio' => 'nullable|date',
            'studentName' => 'required|string|max:255',
            'studentLastName' => 'required|string|max:255',
            'dniStudent' => [
                'required',
                'numeric',
                Rule::unique('students', 'dni')->ignore($studentDni, 'dni')
            ],
            'studentCuil' => ['nullable', 'string', 'size:11'],
            'studentEmail' => ['required', 'email'],
            'studentCelular' => ['required', 'string'],
            'studentCarrer' => 'required|string|max:255',
            'studentIdHidden' => 'required|numeric',


            'tutorName' => 'required|string|max:255',
            'tutorLastName' => 'required|string|max:255',
            'tutorDni' => 'required|numeric',

            'tutorFacuName' => 'required|string|max:255',
            'tutorFacuLastName' => 'required|string|max:255',
            'tutorFacuDni' => 'required|numeric',
            'departament' => 'required',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dni = $this->input('dniStudent');
            $email = $this->input('studentEmail');
            $cuil = $this->input('studentCuil');
            $celular = $this->input('studentCelular');

            $student = Student::where('dni', $dni)->first();

            if ($student) {
                if ($student->email !== $email) {
                    $validator->errors()->add('studentEmail', 'El correo electrónico no coincide con el estudiante registrado.');
                }

                if ($student->cuil) {
                    if ($cuil && (string) $student->cuil !== (string) $cuil) {
                        $validator->errors()->add('studentCuil', 'El CUIL no coincide con el estudiante registrado.');
                    }
                }
                if ((string) $student->phone_numb !== (string) $celular) {
                    $validator->errors()->add('studentCelular', 'El número de celular no coincide con el estudiante registrado.');
                }
            } else {
                // Validar que el email, cuil y celular no existan en otro estudiante
                if (Student::where('email', $email)->exists()) {
                    $validator->errors()->add('studentEmail', 'El correo electrónico ya existe en otro estudiante.');
                }

                if ($cuil && Student::where('cuil', $cuil)->exists()) {
                    $validator->errors()->add('studentCuil', 'El CUIL ya existe en otro estudiante.');
                }

                if (Student::where('phone_numb', $celular)->exists()) {
                    $validator->errors()->add('studentCelular', 'El número de celular ya está registrado en otro estudiante.');
                }
            }
        });
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'companyName.required' => 'La Empresa es obligatoria.',
            'agreementName.required' => 'El nombre del acuerdo es obligatorio.',
            'tasks.required' => 'Deben existir tareas.',
            'fecha_firma.date' => 'La fecha de firma no tiene un formato válido.',
            'fecha_inicio.date' => 'La fecha de inicio no tiene un formato válido.',
            'studentName.required' => 'El nombre del estudiante es obligatorio.',
            'studentLastName.required' => 'El apellido" del estudiante es obligatorio.',
            'dniStudent.required' => 'El campo DNI del estudiante es obligatorio.',
            'dniStudent.unique' => 'El DNI del estudiante ya está registrado.',
            'studentEmail.required' => 'El email es obligatorio.',
            'studentEmail.unique' => 'El correo electrónico del contacto ya existe en otro estudiante.',
            'studentEmail.email' => 'El email debe ser un correo válido.',
            'studentCuil.unique' => 'El CUIL del estudiante ya existe en otro estudiante.',
            'studentCuil.size' => 'El CUIL del estudiante debe tener 11 dígitos.',
            'studentCelular.required' => 'El celular es obligatorio.',
            'studentCelular.unique' => 'El número de celular ya está registrado en otro estudiante.',
            'studentCarrer.required' => 'La carrera es obligatoria.',
            'tutorName.required' => 'El nombre del tutor es obligatorio.',
            'tutorLastName.required' => 'El apellido del tutor es obligatorio.',
            'tutorDni.required' => 'El DNI del tutor es obligatorio.',
            'tutorFacuName.required' => 'El nombre del tutor de facultad es obligatorio.',
            'tutorFacuLastName.required' => 'El apellido del tutor de facultad es obligatorio.',
            'tutorFacuDni.required' => 'El DNI del tutor de facultad es obligatorio.',
        ];
    }
}
