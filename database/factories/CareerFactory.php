<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CareerFactory extends Factory
{

    //Arreglo estático para almacenar los teacher_ids ya utilizados
    protected static $assignedTeachers = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // Obtener un maestro que aún no ha sido asignado
        $teacher = Teacher::whereNotIn('id', self::$assignedTeachers)
            ->inRandomOrder()
            ->first();

        // Agregar el teacher_id al arreglo de asignados
        self::$assignedTeachers[] = $teacher->id;

        return [
            'name' => $this->faker->randomElement([
                'Ingeniería Civil',
                'Ingeniería en Agrimensura',
                'Ingeniería Electromecánica',
                'Ingeniería Industrial',
                'Ingeniería Química',
                'Ingeniería en Sistemas',
                'Licenciatura en Tecnología Médica',
                'Licenciatura en Tecnología de los Alimentos',
                'Ingeniería en Seguridad e Higiene en el Trabajo',
            ]),
            'department_id' => Department::inRandomOrder()->first()->id,
            'coordinator_id' => $teacher->id,
        ];
    }
}
