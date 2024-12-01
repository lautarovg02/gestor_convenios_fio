<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeacherRequest extends FormRequest
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
     * @lautarovg02
     */

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|max:40',
            'lastname' => 'required|string|min:2|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/|max:40',
            'dni' => [
                'required',
                'integer',
                'digits:8',
                Rule::unique('teachers')->ignore($this->teacher),
            ],
            'cuil' => [
                'nullable',
                'integer',
                'digits:11',
                function ($attribute, $value, $fail) {
                    $dni = $this->input('dni');
                    if (!str_contains($value, $dni) || strpos($value, $dni) !== 2) {
                        $fail('El CUIL debe contener el DNI en la posición correspondiente.');
                    }
                },
            ],
            'is_rector' => [
                'required',
                'boolean', // Se separa la regla en su propio elemento.
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $exists = \DB::table('departments')->where('director_id', $this->teacher->id)->exists() ||
                            \DB::table('careers')->where('coordinator_id', $this->teacher->id)->exists();
                        if ($exists) {
                            $fail('Un usuario no puede ser rector y ser director o coordinador al mismo tiempo.');
                        }
                    }
                },
            ],
            'is_dean' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $isRector = $this->input('is_rector');
                        $exists = \DB::table('departments')->where('director_id', $this->teacher->id)->exists() ||
                            \DB::table('careers')->where('coordinator_id', $this->teacher->id)->exists();
                        if ($isRector || $exists) {
                            $fail('Un usuario no puede ser decano y ser rector, director o coordinador al mismo tiempo.');
                        }
                    }
                },
            ],
        ];
    }
    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     * @lautarovg02
     */
    public function messages(): array
    {
        return [
            // Mensajes para el campo "name"
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener mas de :max caracteres.',
            'name.regex' => 'El nombre solo puede contener letras.',

            // Mensajes para el campo "lastname"
            'lastname.required' => 'El campo apellido es obligatorio.',
            'lastname.min' => 'El apellido debe tener al menos :min caracteres.',
            'lastname.max' => 'El apellido no puede tener mas de :max caracteres.',
            'lastname.regex' => 'El apellido solo puede contener letras.',


            // Mensajes para el campo "dni"
            'dni.required' => 'El DNI es un campo obligatorio.',
            'dni.integer' => 'El DNI debe ser un número entero.',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',

            // Mensajes para el campo "cuil"
            'cuil.required' => 'El CUIL es un campo obligatorio.',
            'cuil.integer' => 'El CUIL debe ser un número entero.',
            'cuil.digits' => 'El CUIL debe tener exactamente 11 dígitos.',
            'cuil.custom' => 'El CUIL debe contener el DNI en la posición correspondiente.',

            'custom_validation.is_rector_and_dean' => 'Un usuario no puede ser rector y decano al mismo tiempo.',
        ];
    }
}
