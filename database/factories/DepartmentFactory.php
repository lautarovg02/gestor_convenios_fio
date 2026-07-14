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
        // Selecciona un docente que aún no sea director
        $teacher = Teacher::whereDoesntHave('department')->inRandomOrder()->first();
        // Si no hay docentes disponibles, creamos uno nuevo
        if (!$teacher) {
            $teacher = Teacher::factory()->create();
        }
        return [
            'name' => $this->faker->unique()->randomElement([
                'Ingeniería Civil y Agrimensura',
                'Ingeniería Química',
                'Ingeniería Electromecánica',
                'Ingeniería en Seguridad e Higiene en el Trabajo',
            ]),
            'director_id' => $teacher->id,
        ];
    }
}
