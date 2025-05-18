<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Student;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyEntity;
use App\Models\ContractStatus;
use App\Models\Employee;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SpecificResidenceAgreementsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function specific_residence_agreements_table_exists_with_expected_columns()
    {
        $this->assertTrue(
            Schema::hasTable('specific_residence_agreements'),
            'La tabla specific_residence_agreements no existe'
        );

        $expectedColumns = [
            'id',
            'title',
            'internship_initial_date',
            'task',
            'signing_date',
            'contract_id',
            'student_id',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('specific_residence_agreements', $column),
                "La columna '{$column}' no existe en la tabla specific_residence_agreements"
            );
        }
    }

    /** @test */
    public function it_allows_nullable_fields_to_be_null()
    {
        City::factory()->create();
        CompanyEntity::factory()->create();
        Company::factory()->create();
        Secretary::factory()->create();
        Employee::factory()->count(2)->create();
        Teacher::factory()->create(['is_rector' => true]);
        ContractStatus::factory()->create();
        TypeFrameworkAgreement::factory()->create();

        $contract = Contract::factory()->create();
        $student = Student::factory()->create();

        $agreement = \App\Models\SpecificResidenceAgreement::create([
            'title' => 'Residencia en laboratorio de materiales',
            'internship_initial_date' => now(),
            'task' => 'Realizar ensayos de dureza y resistencia',
            'signing_date' => null,
            'file' => null,
            'contract_id' => $contract->id,
            'student_id' => $student->id,
        ]);

        $this->assertDatabaseHas('specific_residence_agreements', [
            'id' => $agreement->id,
            'signing_date' => null,
            'file' => null,
        ]);
    }

    /** @test */
    public function it_belongs_to_contract_and_student()
    {
        City::factory()->create();
        CompanyEntity::factory()->create();
        Company::factory()->create();
        Secretary::factory()->create();
        Employee::factory()->count(2)->create();
        Teacher::factory()->create(['is_rector' => true]);
        ContractStatus::factory()->create();
        TypeFrameworkAgreement::factory()->create();

        $contract = Contract::factory()->create();
        $student = Student::factory()->create();

        $agreement = \App\Models\SpecificResidenceAgreement::create([
            'title' => 'Residencia en área de diseño',
            'internship_initial_date' => now(),
            'task' => 'Diseño de piezas mecánicas con SolidWorks',
            'signing_date' => now(),
            'file' => null,
            'contract_id' => $contract->id,
            'student_id' => $student->id,
        ]);

        $this->assertInstanceOf(Contract::class, $agreement->contract);
        $this->assertEquals($contract->id, $agreement->contract->id);

        $this->assertInstanceOf(Student::class, $agreement->student);
        $this->assertEquals($student->id, $agreement->student->id);
    }
}

