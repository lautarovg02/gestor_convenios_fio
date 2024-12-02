<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompanyEntity;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyEntity>
 */
class CompanyEntityFactory extends Factory
{

    protected static $entities = [
        'Privada con fines de lucro',
        'Privada sin fines de lucro',
        'Pública con fines de lucro',
        'Pública sin fines de lucro',
        'Mixta con fines de lucro',
        'Mixta sin fines de lucro',
    ];

    protected $model = CompanyEntity::class;

    public function definition(): array
    {
        $name = array_shift(self::$entities);

        // Lanzar una excepción si no quedan nombres en la lista
        if (!$name) {
            throw new \Exception('No hay más nombres disponibles para tipos de entidades.');
        }

        return [
            'name' => $name,
        ];
    }

    public static function resetEntities(): void
    {
        self::$entities = [
            'Privada con fines de lucro',
            'Privada sin fines de lucro',
            'Pública con fines de lucro',
            'Pública sin fines de lucro',
            'Mixta con fines de lucro',
            'Mixta sin fines de lucro',
        ];
    }
}
