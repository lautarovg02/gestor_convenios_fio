<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        // Shallow resource:
        // - CREATE: viene {company}
        // - UPDATE: viene {employee} (y de ahí sacamos company_id)
        $routeEmployee = $this->route('employee'); // modelo o id (en update)
        $employeeId = is_object($routeEmployee) ? $routeEmployee->getKey()
            : (is_scalar($routeEmployee) ? (int)$routeEmployee : null);

        $routeCompany = $this->route('company'); // modelo o id (en create)
        $companyId = is_object($routeCompany) ? $routeCompany->getKey()
            : (is_scalar($routeCompany) ? (int)$routeCompany : null);

        // Si estamos editando y no vino {company}, tomar la del empleado
        if (!$companyId && is_object($routeEmployee)) {
            $companyId = $routeEmployee->company_id;
        }

        // DNI único por empresa
        $dniRule = Rule::unique('employees', 'dni')
            ->when($companyId, fn($q) => $q->where('company_id', $companyId));

        // Al editar, ignorar el propio registro
        if ($employeeId) {
            $dniRule = $dniRule->ignore($employeeId);
        }

        return [
            'name'         => ['required', 'string', 'max:255'],
            'lastname'     => ['required', 'string', 'max:255'],
            'dni'          => ['required', 'digits_between:7,8', $dniRule],
            'cuil'         => ['nullable', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'max:255'],
            'position'     => ['required', 'string', 'max:255'],
            'is_represent' => ['nullable', 'boolean'],
            'phone'        => ['nullable', 'string', 'max:30'],
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
