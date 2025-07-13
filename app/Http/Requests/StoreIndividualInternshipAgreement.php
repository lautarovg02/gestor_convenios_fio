<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndividualInternshipAgreement extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // --- Contrato ---
            'contract_id' => 'required|exists:contracts,id',

// --- Empresa (precargada, solo lectura) ---
'company_denomination'       => 'nullable|string|max:255',
'company_cuit'               => 'nullable|string|max:20',
'company_sector'             => 'nullable|string|max:255',
'company_street'             => 'nullable|string|max:255',
'company_number'             => 'nullable|string|max:10',
'company_city'               => 'nullable|string|max:100',
'company_representante'      => 'nullable|string|max:255',
'company_representante_cuit' => 'nullable|string|max:20',

            // --- Pasante ---
            'student_name' => 'required|string|max:40',
            'student_last_name' => 'required|string|max:40',
            // DNI, único en students:
            'student_dni' => 'required|digits_between:7,8|unique:students,dni',
            // CUIL dividido en 3 inputs, combinados no únicos pero cada parte validada:
            'student_cuil_prefijo' => 'required|digits:2',
            'student_cuil_dni'     => 'required|digits_between:7,8',
            'student_cuil_dv'      => 'required|digits:1',
                        // Email único:
            'student_email' => 'required|email|unique:students,email',
            // Teléfono único, longitud variable pero entre 7 y 15 dígitos:
            'student_phone' => 'required|digits_between:7,15|unique:students,phone_numb',
            'student_ciudad' => 'required|string|max:40',
            // Carrera:
            'student_career' => 'required|string|max:250',

            // Domicilio, ciudad
            'student_domicilio_calle' => 'required|string|max:255',
            'student_domicilio_numero' => 'required|digits_between:1,7',

            // --- Datos de la pasantía ---
            'fecha_firma_marco' => 'required|date',
            'area_pasantia'     => 'required|string|max:255',
            'sitio_pasantia'    => 'required|string|max:255',
            'tareas'            => 'required|string|max:255',
            'periodo_meses'     => 'required|integer|min:1|max:24',
            'fecha_inicio'      => 'required|date',
            'remuneracion_monto' => 'required|numeric|min:0',

            // --- Tutor empresa ---
            'tutor_empresa'     => 'required|string|max:255',
            'tutor_cuil_prefijo' => 'required|digits:2',
            'tutor_cuil_dni'    => 'required|digits_between:7,8',
            'tutor_cuil_dv'     => 'required|digits:1',

            // --- Docente tutor ---
            'docente_nombre'        => 'required|string|max:255',
            'docente_cuil_prefijo'  => 'required|digits:2',
            'docente_cuil_dni'      => 'required|digits_between:7,8',
            'docente_cuil_dv'       => 'required|digits:1',

            // --- Lugar y fecha del convenio ---
            'fecha_convenio' => 'required|date',
            'lugar_convenio' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'string' => 'Debe ingresar texto válido.',
            'numeric' => 'Debe ingresar un número válido.',
            'date' => 'Debe ingresar una fecha válida.',
            'digits' => 'Debe tener :digits dígitos.',
            'digits_between' => 'Debe tener entre :min y :max dígitos.',
            'integer' => 'Debe ser un número entero.',
            'min' => 'El valor mínimo permitido es :min.',
            'max' => 'El valor máximo permitido es :max.',
            'unique' => 'El valor ya está registrado en el sistema.',
            'email' => 'Debe ingresar un email válido.',
        ];
    }
}
