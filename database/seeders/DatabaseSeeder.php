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
        $this->call(ContractStatusSeeder::class);

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
        $secretary->assignRole('Secretaria');
        // Crear perfil Secretary vinculado
        Secretary::firstOrCreate(['user_id' => $secretary->id]);


        // DOCENTE DE PRUEBA
        $docente = User::firstOrCreate(
            ['email' => 'docente@test.com'],
            ['name' => 'Pedro Docente', 'password' => Hash::make('password')]
        );
        $docente->assignRole('Docente');
        // Crear perfil Teacher vinculado
        Teacher::firstOrCreate(
            ['user_id' => $docente->id],
            [
                'name'      => 'Pedro',
                'lastname'  => 'Docente',
                'dni'       => 12345678,
                'cuil'      => '20123456789',
                'is_rector' => false,
                'is_dean'   => false,
            ]
        );



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

        // Crear al menos de 1 a 3 empleados para cada empresa
        Company::all()->each(function ($company) {
            Employee::factory(rand(1, 3))->create([
                'company_id' => $company->id
            ])->each(function ($employee) {
                EmployeePhone::factory()->create([
                    'employee_id' => $employee->id
                ]);
            });
        });

        $teachersForDepartments = Teacher::inRandomOrder()->take(4)->get();
        foreach ($teachersForDepartments as $teacher) {
            Department::factory()->create(['director_id' => $teacher->id]);
        }
        
        // Carreras (Usando el Seeder externo para evitar duplicados o errores de lógica)
        $this->call(CareerSeeder::class);

        Type_Report::factory()->count(5)->create();

        // Deterministico: 1=Pasantía, 2=Residencia, 3=Común
        \App\Models\TypeFrameworkAgreement::insert([
            ['id' => 1, 'type' => 'Convenio Marco de Pasantía', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'type' => 'Convenio Marco de Residencia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'type' => 'Convenio Marco', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Student::factory(80)->create();
        
        // === NEW SEEDING LOGIC FOR CONTRACTS ===
        // Clean creation of 1 unique contract type per company
        $companies = Company::inRandomOrder()->take(15)->get();
        // Types: 1=Pasantia, 2=Residencia, 3=Marco
        
        $companiesCount = 0;
        foreach ($companies as $company) {
            // Assign 1 or 2 framework agreement types to this company
            $typesToAssign = collect([1, 2, 3])->random(rand(1, 2));
            
            foreach ($typesToAssign as $typeId) {
                $contract = Contract::factory()->create([
                    'company_id' => $company->id,
                    'type_framework_agreement_id' => $typeId
                ]);

                // Solo algunos contratos tendrán hijos, el resto quedarán vacíos (y quizás Finalizados)
                $createChildren = rand(0, 1) === 1;

                if ($createChildren) {
                    // Si tiene hijos, nos aseguramos de que el padre NO esté finalizado ni deshabilitado
                    $activeStatusIds = [1, 4, 8]; // SEVyT, SEVyT firma, En ejecución
                    $contract->update(['contract_status_id' => collect($activeStatusIds)->random()]);

                    if ($typeId == 3) {
                        Specific::factory(rand(1, 3))->create([
                            'contract_id' => $contract->id
                        ]);
                        ReportSpecific::factory(rand(0, 1))->create();
                    } elseif ($typeId == 2) {
                        SpecificResidenceAgreement::factory(rand(1, 3))->create([
                            'contract_id' => $contract->id
                        ]);
                        ReportSpecificResidenceAgreement::factory(rand(0, 1))->create();
                    } elseif ($typeId == 1) {
                        IndividualInternshipAgreement::factory(rand(1, 3))->create([
                            'contract_id' => $contract->id
                        ]);
                        ReportIndividualInternshipAgreement::factory(rand(0, 1))->create();
                    }
                }
            }
        }

        $this->call(CareerTeacherSeeder::class);
    }
}