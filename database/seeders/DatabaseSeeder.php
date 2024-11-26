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

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Province::factory()->count(23)->create();
        City::factory()->count(70)->create();
        Company::factory()->count(20)->create();
        Employee::factory()->count(100)->create();
        EmployeePhone::factory()->count(100)->create();
        Secretary::factory()->count(10)->create();
        SecretaryPhone::factory()->count(10)->create();
        Teacher::factory(30)->create();
        Department::factory(4)->create();
        Career::factory(9)->create();
        $this->call(CareerTeacherSeeder::class);
    }
}
