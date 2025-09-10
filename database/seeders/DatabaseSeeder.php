<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // 1) Primero, roles fijos
        $this->call(RolesTableSeeder::class);

        // 2) Admin fijo (útil para login inmediato)
        User::factory()->create([
            'name' => 'secretaria',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role_id' => Role::where('name', 'secretaria')->value('id') ?? 1, // fallback
        ]);

        // 3) Secretaries: crear users y luego perfiles
        User::factory()->count(10)->create([
            'role_id' => 1, // secretary
        ])->each(function (User $u) {
            Secretary::factory()->create([
                'user_id' => $u->id,
                // si tu factory de Secretary pide username y demás, lo completa solo
            ]);
        });

        // 4) Teachers: crear users y luego perfiles
        User::factory()->count(80)->create([
            'role_id' => 2, // teacher
        ])->each(function (User $u) {
            Teacher::factory()->create([
                'user_id' => $u->id,
                // el resto de campos vienen de la factory
            ]);
        });


        // Después de crear los users con role_id=2 y sus Teacher (como ya lo hacés)
        $anyTeacher = Teacher::inRandomOrder()->first();
        if ($anyTeacher) {
            $anyTeacher->update(['is_rector' => true]);
        }

        // (Opcional) marcar un decano distinto
        $another = Teacher::where('id', '!=', $anyTeacher->id)->inRandomOrder()->first();
        if ($another) {
            $another->update(['is_dean' => true]);
        }

        // 5) —— Todo lo demás de tu seeding, igual que antes ——
        Province::factory()->count(23)->create();
        City::factory()->count(70)->create();
        CompanyEntity::factory()->count(6)->create();
        // CSV companies…
        $filePath = 'database\seeders\csv\companyNames.csv';
        $companyNames = \Database\Factories\CompanyFactory::loadCompanyNamesFromCSV($filePath);
        foreach ($companyNames as $name) {
            Company::factory()->create([
                'company_name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
            ]);
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
