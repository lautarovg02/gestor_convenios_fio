<?php

namespace App\Services;


use App\Models\Career;
use Illuminate\Support\Collection;

class CarrerService
{
    /**
     * Obtiene o crea una carrera por su nombre.
     *
     * @param string $name
     * @return Career
     */
    public function getOrCreateByName(string $name): Career
    {
        return Career::firstOrCreate(
            ['name' => $name],
            ['name' => $name]
        );
    }

    /**
     * Busca una carrera por su ID.
     *
     * @param int $id
     * @return Career|null
     */
    public function findCareerById(int $id): ?Career
    {
        return Career::find($id);
    }

    public function getAllCareers(): Collection
    {
        return Career::all();
    }
}