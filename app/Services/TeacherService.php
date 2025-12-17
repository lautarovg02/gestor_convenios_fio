<?php

namespace App\Services;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;

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

public function getAllTeachers(): Collection
    {
        return Teacher::orderBy('lastname')->orderBy('name')->get();
    }

public function getAllRectors(): Collection
    {
        // CORREGIDO: Usamos el booleano 'is_rector' en lugar de string 'category'
        return Teacher::where('is_rector', true)
                      ->orderBy('lastname')
                      ->get();
    }

    
}
