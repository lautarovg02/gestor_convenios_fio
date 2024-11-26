<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{

    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //Obtener un maestro aleatorio para asignarlo como coordinador
        $teacher = Teacher::inRandomOrder()->first();

        return [
            'name' => $this->faker->unique()->randomElement([
                'Ingeniería Civil y Agrimensura',
                'Ingeniería Química',
                'Ingeniería Electromecánica',
                'Ingeniería en Seguridad e Higiene en el Trabajo',
            ]),
            'director_id' => $teacher->id, // DEBEMOS AGRAGAR EN teacher is_department_director
        ];
    }
}
