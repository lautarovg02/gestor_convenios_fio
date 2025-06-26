<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_agreements_index_shows_agreements_for_company()
    {
        
        $company = Company::factory()->create();
        $secretary = Secretary::factory()->create();
        $teacher = Teacher::factory()->create();
        $rector = Teacher::factory()->create(['is_rector' => true]);
        $employee1 = Employee::factory()->create();
        $employee2 = Employee::factory()->create();
        $status = ContractStatus::factory()->create(['id' => 1, 'status' => 'SEVyT']);
        $type = TypeFrameworkAgreement::factory()->create(['type' => 'Marco']);

        // Act: la factory usa estos modelos porque ya existen
        $agreement = Contract::factory()->create([
            'company_id' => $company->id,
            'contract_status_id' => $status->id,
            'type_framework_agreement_id' => $type->id,
            'rector' => $rector->id,
            // no es necesario pasar secretary_id, teacher_id, etc., ya que ya hay registros en la BD
        ]);

        $response = $this->get(route('companies.agreements.index', $company));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('agreements.index');
        $response->assertViewHasAll([
            'company',
            'agreements',
            'statuses',
            'typeFrameworkAgreement',
        ]);
        $response->assertSee($agreement->typeFrameworkAgreement->type);
        $response->assertSee($agreement->status->status);
        $response->assertDontSee('No hay convenios registrados para esta empresa.');
    }

    public function test_agreements_index_shows_empty_message_when_no_agreements()
    {
        
        $company = Company::factory()->create();

        
        $response = $this->get(route('companies.agreements.index', $company));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('agreements.index');
        $response->assertViewHas('agreements');
        $response->assertSee('No hay convenios registrados para esta empresa.');
    }

    public function test_agreements_index_does_not_show_agreements_from_other_companies()
{
    // dos empresas distintas
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    // Se crean datos requeridos por el factory
    Secretary::factory()->create();
    Teacher::factory()->create();
    Teacher::factory()->create(['is_rector' => true]);
    Employee::factory()->create();
    Employee::factory()->create();
    ContractStatus::factory()->create();
    TypeFrameworkAgreement::factory()->create();

    // Se crea un convenio para la empresa 2
    Contract::factory()->create([
        'company_id' => $company2->id,
    ]);

    // Act: se consulta la vista de convenios para la empresa 1 (que no tiene convenios)
    $response = $this->get(route('companies.agreements.index', $company1));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('agreements.index');
    $response->assertSee('No hay convenios registrados para esta empresa.');
}

}
