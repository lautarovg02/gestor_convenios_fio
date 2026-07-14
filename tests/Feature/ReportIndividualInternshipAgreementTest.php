<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{ContractStatus,TypeFrameworkAgreement, Company, Secretary, Teacher, Employee, Contract, IndividualInternshipAgreement, Type_Report, ReportIndividualInternshipAgreement};
use Illuminate\Support\Facades\Schema;


class ReportIndividualInternshipAgreementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function report_individual_internship_agreements_table_exists_with_expected_columns()
    {
        $this->assertTrue(
            Schema::hasTable('report_individual_internship_agreements'),
            'La tabla report_individual_internship_agreements no existe.'
        );

        $expectedColumns = [
            'id',
            'individual_internship_id',
            'individual_internship_contract_id',
            'upload_date',
            'url_report',
            'type_report_id',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('report_individual_internship_agreements', $column),
                "Falta la columna '{$column}' en la tabla report_individual_internship_agreements."
            );
        }
    }
    protected function setUp(): void
    {
        parent::setUp();
    
        // Deshabilitar la verificación de claves foráneas en SQLite
        \DB::statement('PRAGMA foreign_keys=OFF;');
    
        
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

       // Crear individual internship asociado al contrato
       $individualInternship = IndividualInternshipAgreement::factory()->create([
           'contract_id' => $contract->id,
       ]);

       // Crear tipo de reporte
       $typeReport = Type_Report::factory()->create();

       // Crear reporte
       $report = ReportIndividualInternshipAgreement::create([
           'individual_internship_id' => $individualInternship->id,
           'individual_internship_contract_id' => $individualInternship->contract_id,
           'upload_date' => now(),
           'url_report' => 'https://example.com/reporte.pdf',
           'type_report_id' => $typeReport->id,
           'file' => null,
       ]);

       // Verificar que el reporte se guardó en la base de datos
       $this->assertDatabaseHas('report_individual_internship_agreements', [
           'id' => $report->id,
           'url_report' => 'https://example.com/reporte.pdf',
       ]);
   }
}
