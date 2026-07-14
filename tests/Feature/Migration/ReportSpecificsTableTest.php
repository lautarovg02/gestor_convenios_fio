<?php

namespace Tests\Feature;

use App\Models\ReportSpecific;

use App\Models\Type_Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\{City, CompanyEntity, Company, Secretary, Employee, Teacher, Contract, Specific, ContractStatus, TypeFrameworkAgreement};
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ReportSpecificsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function report_specifics_table_exists_with_expected_columns()
    {
        $this->assertTrue(
            Schema::hasTable('report_specifics'),
            'La tabla report_specifics no existe'
        );

        $expectedColumns = [
            'id',
            'specific_id',
            'specific_contract_id',
            'upload_date',
            'type',
            'url_report',
            'type_report_id',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('report_specifics', $column),
                "La columna '{$column}' no existe en la tabla report_specifics"
            );
        }
    }
    public function setUp(): void
    {
        parent::setUp();
    
        // Desactivar claves foráneas en SQLite para evitar errores con relaciones no estándar
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }
    }
    /** @test */
    public function it_belongs_to_specific_type_report_and_specific_contract()
    {
        // Crear dependencias necesarias para poder crear un contrato y un específico
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();
        // Crear registros relacionados
        $specific = Specific::factory()->create();
        $typeReport = Type_Report::factory()->create();

        // Crear ReportSpecific con ambas relaciones a Specific
        $report = ReportSpecific::create([
            'specific_id' => $specific->id,
            'specific_contract_id' => $specific->contract_id,
            'upload_date' => now(),
            'type' => 1,
            'url_report' => 'https://example.com/reporte.pdf',
            'type_report_id' => $typeReport->id,
            'file' => null,
        ]);

        // Verificar relaciones
        $this->assertInstanceOf(Specific::class, $report->specific);
        $this->assertEquals($specific->id, $report->specific->id);

        $this->assertInstanceOf(Specific::class, $report->specificContract);
        $this->assertEquals($specific->contract_id, $report->specificContract->contract_id);

        $this->assertInstanceOf(Type_Report::class, $report->typeReport);
        $this->assertEquals($typeReport->id, $report->typeReport->id);
    }


/** @test */
public function it_allows_url_report_longer_than_254_characters()
{
    City::factory()->create();
    CompanyEntity::factory()->create();
    Company::factory()->create();
    Secretary::factory()->create();
    Employee::factory()->count(2)->create();
    Teacher::factory()->create(['is_rector' => true]);
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();
        // Crear registros relacionados
        $specific = Specific::factory()->create();
        $typeReport = Type_Report::factory()->create();
     
    // Crear una URL con 300 caracteres
    $longUrl = 'https://example.com/' . str_repeat('a', 280);

    // Crear el reporte
    $report = ReportSpecific::create([
        'specific_id' => $specific->id,
        'specific_contract_id' => $specific->contract_id,
        'upload_date' => now(),
        'type' => 1,
        'url_report' => $longUrl,
        'type_report_id' => $typeReport->id,
        'file' => null,
    ]);

    // Verificar que se guardó correctamente
    $this->assertDatabaseHas('report_specifics', [
        'id' => $report->id,
        'url_report' => $longUrl,
    ]);
}







}
