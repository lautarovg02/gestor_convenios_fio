<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployee extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employee = $this->route('employee');
        $employeeId = $employee ? $employee->id : null;

        return [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'dni' => $employeeId
                ? 'required|numeric|digits:8|unique:employees,dni,' . $employeeId . ',id'
                : 'required|numeric|digits:8|unique:employees,dni',
'phones.*.number' => 'required|string|max:20',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es un campo obligatorio.',
            'lastname.required' => 'El apellido es un campo obligatorio.',
            'position.required' => 'El cargo es un campo obligatorio.',
            'dni.required' => 'El DNI es un campo obligatorio.',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique' => 'El DNI ya se encuentra registrado.',
            'phones.*.number.required' => 'El campo no puede ser vacio.'
        ];
    }

    /**
     * Validador adicional para evitar teléfonos duplicados.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allPhones = [];

            if ($this->has('phones')) {
                foreach ($this->input('phones') as $index => $phone) {
                    if (isset($phone['number'])) {
                        $number = $phone['number'];

                        // Ignorar si está marcado para eliminar
                        if (isset($phone['delete']) && $phone['delete'] == 1) {
                            continue;
                        }

                        if (in_array($number, $allPhones)) {
                            $validator->errors()->add("phones.$index.number", 'Este número de celular está duplicado.');
                        } else {
                            $allPhones[] = $number;
                        }
                    }
                }
            }
        });
    }
}
