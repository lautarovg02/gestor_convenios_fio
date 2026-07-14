<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CareerSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // Limpia la tabla antes de insertar datos para evitar duplicados.
        DB::table('careers')->truncate();

        // Asegurarnos de que haya al menos 10 docentes para ser coordinadores
        $teacherCount = \App\Models\Teacher::count();
        if ($teacherCount < 10) {
            \App\Models\Teacher::factory(10 - $teacherCount)->create();
        }

        $teachers = \App\Models\Teacher::inRandomOrder()->take(10)->get();

        $careersData = [
            'Licenciatura en Matemáticas',
            'Licenciatura en Física',
            'Licenciatura en Química',
            'Licenciatura en Ciencias de la Computación',
            'Profesorado en Matemáticas',
            'Profesorado en Física',
            'Profesorado en Química',
            'Ingeniería en Informática',
            'Licenciatura en Estadística',
            'Licenciatura en Biotecnología'
        ];

        $insertData = [];
        foreach ($careersData as $index => $careerName) {
            $insertData[] = [
                'coordinator_id' => $teachers[$index]->id,
                'department_id' => 1, // Asumiendo que el departametno 1 siempre existe
                'name' => $careerName,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('careers')->insert($insertData);
        Schema::enableForeignKeyConstraints();
    }
}

