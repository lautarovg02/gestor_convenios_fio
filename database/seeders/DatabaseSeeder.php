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
use App\Models\CompanyEntity;
use App\Models\Student;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Otras seeders
        Province::factory()->count(23)->create();
        City::factory()->count(70)->create();
        CompanyEntity::factory()->count(6)->create();

        // Ruta del archivo CSV
        $filePath = 'database\seeders\csv\companyNames.csv'; // Cambia según la ubicación real del archivo

        // Obtener los nombres únicos de las compañías
        $companyNames = \Database\Factories\CompanyFactory::loadCompanyNamesFromCSV($filePath);

        foreach ($companyNames as $name) {
            Company::factory()->create([
                'company_name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        Employee::factory()->count(100)->create();
        EmployeePhone::factory()->count(100)->create();
        Secretary::factory()->count(10)->create();
        SecretaryPhone::factory()->count(10)->create();
        Teacher::factory(80)->create();
        Department::factory(4)->create();
        Career::factory(9)->create();
        Student::factory(80)->create();
        $this->call(CareerTeacherSeeder::class);
    }
}
