<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'status' => 'SEVyT'],
            ['id' => 2, 'status' => 'En Departamento'],
            ['id' => 3, 'status' => 'En Coordinación'],
            ['id' => 4, 'status' => 'SEVyT firma'],
            ['id' => 5, 'status' => 'Contraparte'],
            ['id' => 6, 'status' => 'Enviar a CA'],
            ['id' => 7, 'status' => 'En CA'],
            ['id' => 8, 'status' => 'En ejecución'],
            ['id' => 9, 'status' => 'Finalizado'],
            ['id' => 10, 'status' => 'Deshabilitado'],
        ];

        DB::table('contract_statuses')->upsert($statuses, ['id'], ['status']);
    }
}
