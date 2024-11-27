<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyEntity>
 */
class CompanyEntityFactory extends Factory
{


    private static $entities = [
        'Privada con fines de lucro',
        'Privada sin fines de lucro',
        'Pública con fines de lucro',
        'Pública sin fines de lucro',
        'Mixta con fines de lucro',
        'Mixta sin fines de lucro'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Verifica si hay entidades disponibles
        if (empty(self::$entities)) {
            throw new \Exception('No hay más entidades disponibles.');
        }

        // Selecciona aleatoriamente un tipo de entidad
        $index = array_rand(self::$entities);
        $type = self::$entities[$index];

        // Eliminar el tipo elegido del array
        unset(self::$entities[$index]);

        return [
            'name' => $type,
        ];
    }
}
