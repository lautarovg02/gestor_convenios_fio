<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

use App\Models\{
    Company,
    Secretary,
    ContractStatus,
    TypeFrameworkAgreement,
    Employee,
    Teacher,
    Contract,
    SpecificResidenceAgreement,
    Type_Report,
    ReportSpecificResidenceAgreement
};

class ReportSpecificResidenceAgreementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Deshabilitar claves foráneas en SQLite para evitar errores en testing
        \DB::statement('PRAGMA foreign_keys=OFF;');
    }

    /** @test */
    public function report_specific_residence_agreements_table_exists_with_expected_columns()
    {
        $this->assertTrue(
            Schema::hasTable('report_specific_residence_agreements'),
            'La tabla report_specific_residence_agreements no existe.'
        );

        $expectedColumns = [
            'id',
            'specific_residence_agreement_id',
            'specific_residence_agreement_contract_id',
            'upload_date',
            'url_report',
            'type_report_id',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('report_specific_residence_agreements', $column),
                "Falta la columna '{$column}' en la tabla report_specific_residence_agreements."
            );
        }
    }

    /** @test */
    public function it_can_create_a_valid_report_with_relationships()
    {
        // Crear dependencias necesarias
        Company::factory()->create();
        Secretary::factory()->create();
        ContractStatus::factory()->create();
        TypeFrameworkAgreement::factory()->create();
        Employee::factory()->create();
        Teacher::factory()->create(['is_rector' => true]);

        // Crear contrato necesario
        $contract = Contract::factory()->create();

        // Crear specific residence agreement asociado al contrato
        $specificResidenceAgreement = SpecificResidenceAgreement::factory()->create([
            'contract_id' => $contract->id,
        ]);

        // Crear tipo de reporte
        $typeReport = Type_Report::factory()->create();

        // Crear reporte específico
        $report = ReportSpecificResidenceAgreement::create([
            'specific_residence_agreement_id' => $specificResidenceAgreement->id,
            'specific_residence_agreement_contract_id' => $specificResidenceAgreement->contract_id,
            'upload_date' => now(),
            'url_report' => 'https://example.com/reporte.pdf',
            'type_report_id' => $typeReport->id,
            'file' => null,
        ]);

        // Verificar que el reporte se guardó en la base de datos
        $this->assertDatabaseHas('report_specific_residence_agreements', [
            'id' => $report->id,
            'url_report' => 'https://example.com/reporte.pdf',
        ]);
    }
}
