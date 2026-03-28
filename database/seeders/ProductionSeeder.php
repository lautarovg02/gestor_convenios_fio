<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ProductionSeeder extends Seeder
{
    /**
     * Seeder de producción: carga ÚNICAMENTE datos estructurales.
     * NO genera datos de prueba ni usa factories.
     */
    public function run(): void
    {
        // ─────────────────────────────────────────────────────────
        // 1. ROLES Y PERMISOS
        // ─────────────────────────────────────────────────────────
        $this->call(RolePermissionSeeder::class);

        // ─────────────────────────────────────────────────────────
        // 2. ESTADOS DE CONVENIOS
        // ─────────────────────────────────────────────────────────
        $this->call(ContractStatusSeeder::class);

        // ─────────────────────────────────────────────────────────
        // 3. TIPOS DE CONVENIO MARCO  (IDs determinísticos)
        // ─────────────────────────────────────────────────────────
        DB::table('type_framework_agreements')->upsert(
            [
                ['id' => 1, 'type' => 'Convenio Marco de Pasantía',  'created_at' => now(), 'updated_at' => now()],
                ['id' => 2, 'type' => 'Convenio Marco de Residencia','created_at' => now(), 'updated_at' => now()],
                ['id' => 3, 'type' => 'Convenio Marco',              'created_at' => now(), 'updated_at' => now()],
            ],
            ['id'],
            ['type']
        );

        // ─────────────────────────────────────────────────────────
        // 4. DEPARTAMENTO GENÉRICO (necesario para asociar carreras)
        //    El director se asigna después desde la interfaz.
        // ─────────────────────────────────────────────────────────
        $departmentId = DB::table('departments')->insertGetId([
            'name'       => 'Departamento General',
            'director_id'=> null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─────────────────────────────────────────────────────────
        // 5. CARRERAS DE LA FACULTAD
        //    coordinator_id = null hasta que se asigne un docente.
        // ─────────────────────────────────────────────────────────
        $careers = [
            'Licenciatura en Matemáticas',
            'Licenciatura en Física',
            'Licenciatura en Química',
            'Licenciatura en Ciencias de la Computación',
            'Profesorado en Matemáticas',
            'Profesorado en Física',
            'Profesorado en Química',
            'Ingeniería en Informática',
            'Licenciatura en Estadística',
            'Licenciatura en Biotecnología',
        ];

        foreach ($careers as $careerName) {
            DB::table('careers')->insertOrIgnore([
                'name'           => $careerName,
                'coordinator_id' => null,
                'department_id'  => $departmentId,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // ─────────────────────────────────────────────────────────
        // 6. USUARIO ADMINISTRADOR INICIAL
        //    Cambiar email y contraseña antes de entregar.
        // ─────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@fio.uner.edu.ar'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Admin1234!'),
            ]
        );
        $admin->assignRole('Admin');

        $this->command->info('✅ ProductionSeeder ejecutado correctamente.');
        $this->command->info('⚠️  Recordá cambiar la contraseña del Admin en el primer acceso.');
    }
}
