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
        $index = array_rand(self::$entities); // Selecciona un índice al azar
        return [
            'name' => self::$entities[$index], // Devuelve el nombre de la entidad seleccionada
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
