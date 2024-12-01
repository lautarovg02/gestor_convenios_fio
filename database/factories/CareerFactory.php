<?php

namespace Database\Factories;

use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\Teacher;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CareerFactory extends Factory
{

    protected static $assignedTeachers = [];

    //Arreglo estático para almacenar las carreras
    protected static $careerNames = [
        'Ingeniería Civil',
        'Ingeniería en Agrimensura',
        'Ingeniería Electromecánica',
        'Ingeniería Industrial',
        'Ingeniería Química',
        'Ingeniería en Sistemas',
        'Licenciatura en Tecnología Médica',
        'Licenciatura en Tecnología de los Alimentos',
        'Ingeniería en Seguridad e Higiene en el Trabajo',
    ];

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

                // Obtener un nombre de la lista y eliminarlo
                $name = array_shift(self::$careerNames);

                // Lanzar una excepción si no quedan nombres en la lista
                if (!$name) {
                    throw new \Exception('No hay más nombres disponibles para la creación de carreras.');
                }

        return [
            'name' => $name,
            'department_id' => Department::inRandomOrder()->first()->id,
            'coordinator_id' => $teacher->id,
        ];
    }
}

