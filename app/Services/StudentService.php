<?php

namespace App\Services;

use App\Models\Student;

class StudentService {

    /**
     * Busca o crea un estudiante por DNI.
     *
     * @param array $data
     * @return Student
     */

     

    public function findOrCreateByDni($data): Student

    {
        return Student::firstOrCreate(
            ['dni' => $data['dni']],
            [
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone_numb' => $data['phone'],
                'cuil' => $data['cuil'] ?? null,
                'dni' => $data['dni'],
                'carrer' => $data['career'] ?? null,
            ]
        );
    }
}