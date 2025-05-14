<?php

namespace Database\Factories;

use App\Models\TypeFrameworkAgreement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypeFrameworkAgreement>
 */
class TypeFrameworkAgreementFactory extends Factory
{

    protected $model = TypeFrameworkAgreement::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'=>$this->faker->unique()->randomElement(['Convenio Marco', 'Convenio Marco de Pasantía', 'Convenio Marco de Residencia'])
        ];
    }
}
