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
use App\Models\SecretaryPhone;
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
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Primero, roles fijos (asumo que RolesTableSeeder crea los roles con nombres correctos)
        $this->call(RolesTableSeeder::class);

        // Obtener ids de roles de forma segura (fallback al primer role si no existe)
        $adminRoleId = Role::where('name', 'admin')->value('id')
            ?? Role::where('name', 'administrador')->value('id')
            ?? Role::first()->id;

        $secretaryRoleId = Role::where('name', 'secretary')->value('id')
            ?? Role::where('name', 'secretaria')->value('id')
            ?? Role::first()->id;

        $teacherRoleId = Role::where('name', 'teacher')->value('id')
            ?? Role::where('name', 'docente')->value('id')
            ?? Role::first()->id;

        // 2) Admin fijo (útil para login inmediato)
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRoleId,
        ]);

        // 3) Secretaries: crear users y luego perfiles en secretaries
        // Usamos sufijo incremental en email para evitar colisiones
        $secretaryCount = 10;
        for ($i = 1; $i <= $secretaryCount; $i++) {
            $user = User::factory()->create([
                'name' => "Secretary {$i}",
                'email' => "secretary{$i}@test.com",
                'password' => Hash::make('1234'),
                'role_id' => $secretaryRoleId,
            ]);

            // Crear el perfil de secretary (ajustá campos según tu migración)
            Secretary::factory()->create([
                'user_id' => $user->id,
                // si tu factory requiere username u otros campos, la factory los cubrirá
            ]);
        }

        // 4) Teachers: crear users y luego perfiles en teachers
        $teacherCount = 80;
        for ($i = 1; $i <= $teacherCount; $i++) {
            $user = User::factory()->create([
                'name' => "Teacher {$i}",
                'email' => "teacher{$i}@test.com",
                'password' => Hash::make('1234'),
                'role_id' => $teacherRoleId,
            ]);

            // Crear registro en teachers (ajustá campos según tu migración)
            Teacher::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // Después de crear los users con role_id=teacher y sus Teacher
        $anyTeacher = Teacher::inRandomOrder()->first();
        if ($anyTeacher) {
            $anyTeacher->update(['is_rector' => true]);

            // (Opcional) marcar un decano distinto si existe otro teacher
            $another = Teacher::where('id', '!=', $anyTeacher->id)->inRandomOrder()->first();
            if ($another) {
                $another->update(['is_dean' => true]);
            }
        }

        // 5) —— Resto del seeding que ya tenías ——
        Province::factory()->count(23)->create();
        City::factory()->count(70)->create();
        CompanyEntity::factory()->count(6)->create();

        // CSV companies: usar ruta segura
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
            // Si no existe CSV, crear algunas companies de ejemplo
            Company::factory()->count(10)->create();
        }

        Employee::factory()->count(100)->create();
        EmployeePhone::factory()->count(100)->create();

        // departamentos con docentes
        $teachersForDepartments = Teacher::inRandomOrder()->take(4)->get();
        foreach ($teachersForDepartments as $teacher) {
            Department::factory()->create(['director_id' => $teacher->id]);
        }

        Career::factory(9)->create();
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
