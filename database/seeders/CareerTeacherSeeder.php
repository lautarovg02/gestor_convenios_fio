<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Career;
use App\Models\Teacher;

class CareerTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Se obtienen todas las carreras
        $careers = Career::all();

        // Asocia un número aleatorio de Teachers a cada Career
        foreach ($careers as $career) {

            // Asocia entre 1 y 5 Teachers aleatorios a cada Career
            $teachers = Teacher::inRandomOrder()->take(rand(2, 5))->pluck('id');

            // Asocia los IDs de los profesores a la carrera
            $career->teachers()->attach($teachers);
        };
    }
}
