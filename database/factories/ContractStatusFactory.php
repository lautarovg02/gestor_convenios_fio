<?php

namespace Database\Factories;

use App\Models\ContractStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContractStatus>
 */
class ContractStatusFactory extends Factory
{

    protected $model = ContractStatus::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status'=>$this->faker->unique()->randomElement(['En Departamento', 'En Coordinación', 'SEVyT', 'SEVyT-Firma',
                                                             'Contraparte', 'SEVyT-Enviar a CA', 'En CA', 'En Ejecución',
                                                             'Finalizado']),
            'time_limit'=>$this->faker->optional()->time('H:i:s')
        ];
    }
}
