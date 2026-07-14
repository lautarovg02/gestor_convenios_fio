<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SpecificResidenceAgreement;
use App\Models\Student;
use App\Models\Contract;
use App\Models\Company;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;

class SpecificResidenceAgreementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear registros necesarios para que el factory de Contract funcione
        Company::factory()->create();
        Secretary::factory()->create();
        Teacher::factory()->create(['is_rector' => true]);
        Employee::factory()->count(2)->create();
        ContractStatus::factory()->create();
        TypeFrameworkAgreement::factory()->create();
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->post(route('specificResidenceAgreement.store'), []);

        $response->assertSessionHasErrors([
            'companyName',
            'companyRepresentative',
            'contract_id',
            'companyId',
            'agreementName',
            'tasks',
            'studentName',
            'studentLastName',
            'dniStudent',
            'studentEmail',
            'studentCelular',
            'studentCarrer',
            'tutorName',
            'tutorLastName',
            'tutorDni',
            'tutorFacuName',
            'tutorFacuLastName',
            'tutorFacuDni',
            'departament',
        ]);
    }

    /** @test */
    public function it_creates_specific_residence_agreement_successfully()
    {
        // Obtener los modelos creados en setUp para usar en contrato
        $company = Company::first();
        $secretary = Secretary::first();
        $teacher = Teacher::where('is_rector', true)->first();
        $employees = Employee::all();
        $contractStatus = ContractStatus::first();
        $typeFrameworkAgreement = TypeFrameworkAgreement::first();

        // Crear contrato con datos reales de relaciones
        $contract = Contract::factory()->create([
            'company_id' => $company->id,
            'secretary_id' => $secretary->id,
            'teacher_id' => $teacher->id,
            'contact_employee_id' => $employees->first()->id,
            'representative_employee_id' => $employees->last()->id,
            'rector' => $teacher->id,
            'contract_status_id' => $contractStatus->id,
            'type_framework_agreement_id' => $typeFrameworkAgreement->id,
        ]);

        $postData = [
            'companyName' => $company->company_name,
            'companyRepresentative' => 'Representante Ejemplo',
            'contract_id' => $contract->id,
            'companyId' => $company->id,
            'agreementName' => 'Acuerdo Test',
            'tasks' => 'Tarea 1, Tarea 2',
            'fecha_firma' => '2025-06-28',
            'fecha_inicio' => '2025-07-01',
            'studentName' => 'Juan',
            'studentLastName' => 'Pérez',
            'dniStudent' => '12345678',
            'studentEmail' => 'juan.perez@example.com',
            'studentCelular' => '1234567890',
            'studentCarrer' => 'Ingeniería',
            'tutorName' => 'Tutor Nombre',
            'tutorLastName' => 'Tutor Apellido',
            'tutorDni' => '87654321',
            'tutorFacuName' => 'TutorFacu Nombre',
            'tutorFacuLastName' => 'TutorFacu Apellido',
            'tutorFacuDni' => '11223344',
            'departament' => 'Departamento Ejemplo',
        ];

        $response = $this->post(route('specificResidenceAgreement.store'), $postData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('specific_residence_agreements', [
            'title' => 'Acuerdo Test',
            'contract_id' => $contract->id,
        ]);
    }

    /** @test */
    public function it_does_not_allow_duplicate_specific_residence_agreements()
    {
        $company = Company::first();
        $secretary = Secretary::first();
        $teacher = Teacher::where('is_rector', true)->first();
        $employees = Employee::all();
        $contractStatus = ContractStatus::first();
        $typeFrameworkAgreement = TypeFrameworkAgreement::first();

        $contract = Contract::factory()->create([
            'company_id' => $company->id,
            'secretary_id' => $secretary->id,
            'teacher_id' => $teacher->id,
            'contact_employee_id' => $employees->first()->id,
            'representative_employee_id' => $employees->last()->id,
            'rector' => $teacher->id,
            'contract_status_id' => $contractStatus->id,
            'type_framework_agreement_id' => $typeFrameworkAgreement->id,
        ]);

        SpecificResidenceAgreement::factory()->create([
            'contract_id' => $contract->id,
            'title' => 'Acuerdo existente',
        ]);

        $postData = [
            'companyName' => $company->company_name,
            'companyRepresentative' => 'Representante Ejemplo',
            'contract_id' => $contract->id,
            'companyId' => $company->id,
            'agreementName' => 'Acuerdo existente',
            'tasks' => 'Tarea ejemplo',
            'fecha_firma' => '2025-06-28',
            'fecha_inicio' => '2025-07-01',
            'studentName' => 'Juan',
            'studentLastName' => 'Pérez',
            'dniStudent' => '12345678',
            'studentEmail' => 'juan.perez@example.com',
            'studentCelular' => '1234567890',
            'studentCarrer' => 'Ingeniería',
            'tutorName' => 'Tutor Nombre',
            'tutorLastName' => 'Tutor Apellido',
            'tutorDni' => '87654321',
            'tutorFacuName' => 'TutorFacu Nombre',
            'tutorFacuLastName' => 'TutorFacu Apellido',
            'tutorFacuDni' => '11223344',
            'departament' => 'Departamento Ejemplo',
        ];

        $response = $this->post(route('specificResidenceAgreement.store'), $postData);

        $response->assertSessionHas('existeAcuerdo', true);
    }
}