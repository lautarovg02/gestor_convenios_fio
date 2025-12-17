<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Company;
use App\Models\Employee;
use App\Models\City;
use App\Models\Department;
use App\Models\EmployeePhone;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\Career;
use App\Models\Type_Report;
use App\Models\CompanyEntity;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use App\Models\Specific;
use App\Models\SpecificResidenceAgreement;
use App\Models\IndividualInternshipAgreement;
use App\Models\ReportSpecific;
use App\Models\ReportIndividualInternshipAgreement;
use App\Models\ReportSpecificResidenceAgreement;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Roles y Permisos (Spatie)
        $this->call(RolePermissionSeeder::class);

        // ----------------------------------------------------
        // 2. CREACIÓN DE USUARIOS CLAVE
        // ----------------------------------------------------

        // ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->assignRole('Admin');

        // DIRECTOR
        $director = User::firstOrCreate(
            ['email' => 'director@test.com'],
            ['name' => 'Juan Director', 'password' => Hash::make('password')]
        );
        $director->assignRole('Director');

        // COORDINADOR
        $coordinador = User::firstOrCreate(
            ['email' => 'coordinador@test.com'],
            ['name' => 'Maria Coordinadora', 'password' => Hash::make('password')]
        );
        $coordinador->assignRole('Coordinador');

        // SECRETARIA DE PRUEBA
        $secretary = User::firstOrCreate(
            ['email' => 'secretary@test.com'],
            ['name' => 'Maria Secretaria', 'password' => Hash::make('password')]
        );
        // Asegúrate que en RolePermissionSeeder el nombre sea 'Secretaria' (con mayúscula)
        $secretary->assignRole('Secretaria'); 
        

        $docente = User::firstOrCreate(
            ['email' => 'docente@test.com'],
            ['name' => 'Pedro Docente', 'password' => Hash::make('password')]
        );
        $docente->assignRole('Docente');



        // ----------------------------------------------------
        // 3. GENERACIÓN MASIVA
        // ----------------------------------------------------

        // Secretarias aleatorias
        User::factory(1)->create()->each(function ($user) {
            $user->assignRole('Secretaria');
            Secretary::factory()->create(['user_id' => $user->id]);
        });

        // Profesores aleatorios
        User::factory(1)->create()->each(function ($user) {
            // CORRECCIÓN AQUÍ: Cambiamos 'Profesor' por 'Docente'
            // para coincidir con lo que definiste en RolePermissionSeeder
            $user->assignRole('Docente'); 
            
            Teacher::factory()->create(['user_id' => $user->id]);
        });

        // Asignar Rector y Decano
        $anyTeacher = Teacher::inRandomOrder()->first();
        if ($anyTeacher) {
            $anyTeacher->update(['is_rector' => true]);
            $another = Teacher::where('id', '!=', $anyTeacher->id)->inRandomOrder()->first();
            if ($another) $another->update(['is_dean' => true]);
        }

        // ----------------------------------------------------
        // 4. DATOS DEL SISTEMA
        // ----------------------------------------------------
        
        Province::factory()->count(23)->create();
        City::factory()->count(70)->create();
        CompanyEntity::factory()->count(6)->create();

        // Carga de Compañías
        $filePath = database_path('seeders/csv/companyNames.csv');
        if (file_exists($filePath) && method_exists(\Database\Factories\CompanyFactory::class, 'loadCompanyNamesFromCSV')) {
            $companyNames = \Database\Factories\CompanyFactory::loadCompanyNamesFromCSV($filePath);
            foreach ($companyNames as $name) {
                Company::factory()->create([
                    'company_name' => $name,
                    'slug' => Str::slug($name),
                ]);
            }
        } else {
            Company::factory()->count(10)->create();
        }

        Employee::factory()->count(100)->create();
        EmployeePhone::factory()->count(100)->create();

        $teachersForDepartments = Teacher::inRandomOrder()->take(4)->get();
        foreach ($teachersForDepartments as $teacher) {
            Department::factory()->create(['director_id' => $teacher->id]);
        }
        
        // Carreras (Usando el Seeder externo para evitar duplicados o errores de lógica)
        $this->call(CareerSeeder::class);

        Type_Report::factory()->count(5)->create();
        TypeFrameworkAgreement::factory(3)->create();
        ContractStatus::factory(10)->create();
        Contract::factory(2)->create();
        Student::factory(80)->create();
        Specific::factory(6)->create();
        SpecificResidenceAgreement::factory(4)->create();
        IndividualInternshipAgreement::factory(5)->create();
        ReportSpecific::factory(4)->create();
        ReportSpecificResidenceAgreement::factory(4)->create();
        ReportIndividualInternshipAgreement::factory(4)->create();

        $this->call(CareerTeacherSeeder::class);
    }
}