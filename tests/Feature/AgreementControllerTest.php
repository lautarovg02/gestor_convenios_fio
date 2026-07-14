<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\Employee;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AgreementControllerTest extends TestCase
{
    use RefreshDatabase;


/** @test */
public function it_lists_contracts_ordered_by_creation_date_descending()
{
    // Crear relaciones necesarias
    $company = Company::factory()->create();
    $secretary = Secretary::factory()->create();
    $teacher = Teacher::factory()->create();
    $rector = Teacher::factory()->create(['is_rector' => true]);
    $contactEmployee = Employee::factory()->create(['company_id' => $company->id]);
    $representativeEmployee = Employee::factory()->create(['company_id' => $company->id]);
    $status = ContractStatus::factory()->create(['id' => 5, 'status' => 'Activo']);
    $type = TypeFrameworkAgreement::factory()->create(['type' => 'Convenio Marco de Pasantía']);

    // Contrato más reciente
    $newerContract = Contract::create([
        'signing_date' => Carbon::now()->subDays(1),
        'url_certificate_afip' => null,
        'url_statute' => null,
        'url_assignment_authorities' => null,
        'company_id' => $company->id,
        'secretary_id' => $secretary->id,
        'teacher_id' => $teacher->id,
        'contact_employee_id' => $contactEmployee->id,
        'representative_employee_id' => $representativeEmployee->id,
        'rector' => $rector->id,
        'contract_status_id' => $status->id,
        'type_framework_agreement_id' => $type->id,
        'file' => null,
        'creation_date' => Carbon::now()->subDays(1),
    ]);

    // Contrato más antiguo
    $olderContract = Contract::create([
        'signing_date' => Carbon::now()->subDays(10),
        'url_certificate_afip' => null,
        'url_statute' => null,
        'url_assignment_authorities' => null,
        'company_id' => $company->id,
        'secretary_id' => $secretary->id,
        'teacher_id' => $teacher->id,
        'contact_employee_id' => $contactEmployee->id,
        'representative_employee_id' => $representativeEmployee->id,
        'rector' => $rector->id,
        'contract_status_id' => $status->id,
        'type_framework_agreement_id' => $type->id,
        'file' => null,
        'creation_date' => Carbon::now()->subDays(10),
    ]);

    // Hacer request
    $response = $this->get(route('agreements.index'));

    $response->assertStatus(200);
    $agreements = $response->viewData('agreements');

    // Verificar orden descendente
    $this->assertEquals(
        $newerContract->id,
        $agreements->first()->id,
        'El contrato más reciente debería estar primero en la lista'
    );


$this->assertNotEmpty($agreements, 'La lista de convenios no debería estar vacía');
$this->assertCount(2, $agreements); // Por si esperás 2 válidos
$this->assertTrue($agreements->contains($newerContract));
$this->assertTrue($agreements->contains($olderContract));
}

}
