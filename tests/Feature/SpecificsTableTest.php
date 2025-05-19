<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\{City, CompanyEntity, Company, Secretary, Employee, Teacher, Contract, Specific, ContractStatus, Province, TypeFrameworkAgreement};



class SpecificsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function specifics_table_is_created_with_expected_columns_and_foreign_keys()
    {
      
        // Verificar que la tabla 'specifics' exista
        $this->assertTrue(Schema::hasTable('specifics'), 'La tabla specifics no fue creada');

        // Verificar que las columnas existen
        $expectedColumns = [
            'id',
            'contract_id',
            'signing_date',
            'objective',
            'commitment_parties',
            'responsable_control_company',
            'responsable_control_fio',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('specifics', $column),
                "Falta la columna: $column"
            );
        }

      
    }

/** @test */
public function it_allows_nullable_fields_to_be_null()
{
    // Crear dependencias requeridas
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();

    // Crear contrato necesario
    $contract = Contract::factory()->create();

    // Crear Specific con campos nulos permitidos
    $specific = Specific::create([
        'contract_id' => $contract->id,
        'signing_date' => null,
        'objective' => 'Objetivo de prueba',
        'commitment_parties' => 'Parte A, Parte B',
        'responsable_control_company' => null,
        'responsable_control_fio' => null,
        'file' => null,
    ]);

    // Verificar en base de datos
    $this->assertDatabaseHas('specifics', [
        'id' => $specific->id,
        'signing_date' => null,
        'responsable_control_company' => null,
        'responsable_control_fio' => null,
        'file' => null,
    ]);
}

/** @test */
public function it_fails_when_required_fields_are_missing()
{
    $this->expectException(QueryException::class);

    // Crear dependencias mínimas
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();

    $contract = Contract::factory()->create();

    // Falla porque faltan campos requeridos
    Specific::create([
        'contract_id' => $contract->id,
    ]);
}

/** @test */
public function specific_belongs_to_contract()
{
    // Crear un contrato
    // Crear dependencias mínimas
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();

    $contract = \App\Models\Contract::factory()->create();

    // Crear un Specific asociado a ese contrato
    $specific = \App\Models\Specific::create([
        'contract_id' => $contract->id,
        'objective' => 'Objetivo prueba',
        'commitment_parties' => 'Partes involucradas',
    ]);

    // Verificar que en la DB Specific tiene el contract_id correcto
    $this->assertDatabaseHas('specifics', [
        'id' => $specific->id,
        'contract_id' => $contract->id,
    ]);
}

/** @test */
public function contract_has_many_specifics_relation_works()
{
    // Crear un contrato
    // Crear dependencias mínimas
    Province::factory()->create(); //No olvidar crear una provincia antes que una ciudad.
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();
    $contract = \App\Models\Contract::factory()->create();

    $specifics = \App\Models\Specific::factory()->count(3)->create([
        'contract_id' => $contract->id,
    ]);

    $this->assertCount(3, $contract->specifics);
    $this->assertTrue($contract->specifics->contains($specifics->first()));
}









}
