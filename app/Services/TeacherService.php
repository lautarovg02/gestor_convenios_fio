<?php

namespace App\Services;

use App\Models\Teacher;

class TeacherService
{
    /**
     * Busca un docente por DNI. Si no existe, lo crea.
     *
     * @param array $data Los datos del docente (debe incluir 'dni' como mínimo).
     * @return Teacher
     */
    public function findOrCreateByDni(array $data): Teacher
    {
        return Teacher::firstOrCreate(
            ['dni' => $data['dni']],
            $data
        );
    }
}
