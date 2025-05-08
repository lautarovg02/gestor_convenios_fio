<?php

namespace Tests\Feature;

use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractStatusAndTypeAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_contract_status()
    {
        $contractStatus = ContractStatus::factory()->create([
            'status' => 'Activo',
            'time_limit' => '12:00:00'
        ]);

        $this->assertDatabaseHas('contract_statuses', [
            'status' => 'Activo',
            'time_limit' => '12:00:00',
        ]);
    }

    public function test_it_creates_a_type_framework_agreement()
    {
        $typeAgreement = TypeFrameworkAgreement::factory()->create([
            'type' => 'Convenio Específico'
        ]);

        $this->assertDatabaseHas('type_framework_agreements', [
            'type' => 'Convenio Específico',
        ]);
    }
}