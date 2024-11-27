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
        // Nuevas carreras
        'Licenciatura en Administración de Empresas',
        'Licenciatura en Contaduría Pública',
        'Licenciatura en Psicología',
        'Licenciatura en Derecho',
        'Licenciatura en Comunicación Social',
        'Licenciatura en Educación',
        'Licenciatura en Trabajo Social',
        'Licenciatura en Mercadotecnia',
        'Licenciatura en Diseño Gráfico',
        'Licenciatura en Relaciones Internacionales',
        'Licenciatura en Ciencias Políticas',
        'Licenciatura en Biología',
        'Licenciatura en Matemáticas',
        'Licenciatura en Química',
        'Licenciatura en Física',
        'Licenciatura en Ciencias Ambientales',
        'Ingeniería en Telecomunicaciones',
        'Ingeniería en Software',
        'Ingeniería de Transporte',
        'Ingeniería en Energías Renovables',
        'Licenciatura en Gastronomía',
        'Licenciatura en Artes Visuales',
        'Licenciatura en Música',
        'Licenciatura en Filosofía',
        'Licenciatura en Historia',
        'Licenciatura en Sociología',
        'Licenciatura en Deportes',
        'Ingeniería en Robótica',
        'Licenciatura en Marketing Digital',
        'Licenciatura en Biotecnología',
        'Ingeniería en Sistemas de Información',
        'Licenciatura en Asistencia Ejecutiva',
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
