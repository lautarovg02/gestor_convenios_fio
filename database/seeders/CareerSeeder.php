<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CareerSeeder extends Seeder
{
    public function run()
    {
        // Desactiva restricciones de clave foránea para evitar problemas durante el seeding.
        Schema::disableForeignKeyConstraints();

        // Limpia la tabla antes de insertar datos para evitar duplicados.
        DB::table('careers')->truncate();

        // Inserta las carreras
        DB::table('careers')->insert([
            [
                'coordinator_id' => 1,
                'department_id' => 1,
                'name' => 'Licenciatura en Matemáticas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 2,
                'department_id' => 1,
                'name' => 'Licenciatura en Física',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 3,
                'department_id' => 1,
                'name' => 'Licenciatura en Química',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 4,
                'department_id' => 1,
                'name' => 'Licenciatura en Ciencias de la Computación',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 5,
                'department_id' => 1,
                'name' => 'Profesorado en Matemáticas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 6,
                'department_id' => 1,
                'name' => 'Profesorado en Física',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 7,
                'department_id' => 1,
                'name' => 'Profesorado en Química',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 8,
                'department_id' => 1,
                'name' => 'Ingeniería en Informática',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 9,
                'department_id' => 1,
                'name' => 'Licenciatura en Estadística',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coordinator_id' => 10,
                'department_id' => 1,
                'name' => 'Licenciatura en Biotecnología',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Reactiva restricciones de clave foránea.
        Schema::enableForeignKeyConstraints();
    }
}

