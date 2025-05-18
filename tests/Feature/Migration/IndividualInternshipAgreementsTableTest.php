<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\{City, CompanyEntity, Company, Secretary, Employee, Teacher, Contract, Specific, ContractStatus, TypeFrameworkAgreement};
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class IndividualInternshipAgreementsTableTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    //Validar la tabla y los campos requeridos
    public function individual_internship_agreements_table_exists_with_expected_columns()
    {
        $this->assertTrue(
            Schema::hasTable('individual_internship_agreements'),
            'La tabla individual_internship_agreements no existe'
        );

        $expectedColumns = [
            'id',
            'months_quantity',
            'task',
            'internship_initial_date',
            'assignment',
            'area',
            'signing_date',
            'contract_id',
            'student_id',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('individual_internship_agreements', $column),
                "La columna '{$column}' no existe en la tabla individual_internship_agreements"
            );
        }
    }

/** @test */
public function it_allows_nullable_fields_to_be_null()
{
    // Crear dependencias necesarias
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();

    // Crear contrato y estudiante requeridos
    $contract = Contract::factory()->create();
    $student = Student::factory()->create();

    // Crear el registro con campos nulos permitidos
    $agreement = \App\Models\IndividualInternshipAgreement::create([
        'months_quantity' => 6,
        'task' => 'Desarrollar tareas administrativas',
        'internship_initial_date' => now(),
        'assignment' => 'Administración de Recursos',
        'area' => 'Recursos Humanos',
        'signing_date' => null,
        'file' => null,
        'contract_id' => $contract->id,
        'student_id' => $student->id,
    ]);

    // Verificar que el registro se haya creado con los campos nulos
    $this->assertDatabaseHas('individual_internship_agreements', [
        'id' => $agreement->id,
        'signing_date' => null,
        'file' => null,
    ]);
}

/** @test */
 // Verificar que las relaciones funcionen
public function it_belongs_to_contract_and_student()
{
    // Crear dependencias necesarias
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();

    // Crear contrato y estudiante
    $contract = Contract::factory()->create();
    $student = Student::factory()->create();

    // Crear el acuerdo individual
    $agreement = \App\Models\IndividualInternshipAgreement::create([
        'months_quantity' => 3,
        'task' => 'Soporte técnico',
        'internship_initial_date' => now(),
        'assignment' => 'Infraestructura',
        'area' => 'TI',
        'signing_date' => now(),
        'file' => null,
        'contract_id' => $contract->id,
        'student_id' => $student->id,
    ]);

    // Verificar que las relaciones funcionen
    $this->assertInstanceOf(Contract::class, $agreement->contract);
    $this->assertEquals($contract->id, $agreement->contract->id);

    $this->assertInstanceOf(Student::class, $agreement->student);
    $this->assertEquals($student->id, $agreement->student->id);
}







}
