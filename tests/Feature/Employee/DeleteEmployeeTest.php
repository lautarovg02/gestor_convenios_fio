<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;
    protected Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create();

        $this->employee = Employee::factory()->create([
            'company_id' => $this->company->id,
            'name'       => 'Ana',
            'lastname'   => 'Pérez',
            'dni'        => '35555555',
            'position'   => 'Responsable Comercial',
            'email'      => 'ana.perez@acme.com',
        ]);
    }

    /** @test */
    public function elimina_un_empleado_correctamente()
    {
        $response = $this->delete(route('employees.destroy', $this->employee));

        $response->assertStatus(302);

        // HARD DELETE:
        $this->assertDatabaseMissing('employees', ['id' => $this->employee->id]);

        // Si usás SoftDeletes, reemplazá por:
        // $this->assertSoftDeleted('employees', ['id' => $this->employee->id]);
    }

    /**
     * @test
     * (Opcional) Si tu dominio NO permite eliminar empleados referenciados en convenios,
     * este test verifica que el sistema lo bloquee de forma elegante.
     * Comentalo si todavía no tenés las FK/lógica de protección.
     */
    /** @test */
    /** @test */
    public function no_permite_eliminar_si_tiene_referencias_relacionadas()
    {
        $secretary = \App\Models\Secretary::factory()->create();
        $teacher   = \App\Models\Teacher::factory()->create();
        $rector    = \App\Models\Teacher::factory()->create(['is_rector' => true]);
        $status    = \App\Models\ContractStatus::factory()->create();
        $type      = \App\Models\TypeFrameworkAgreement::factory()->create();

        $repEmployee = \App\Models\Employee::factory()->create([
            'company_id' => $this->company->id,
            'dni'        => '99999999',
        ]);

        // Usá la factory autosuficiente y sobreescribí lo que te importa
        $contract = \App\Models\Contract::factory()
            ->forCompany($this->company) // <-- si usaste el helper; si no, seteá los campos abajo
            ->create([
                'company_id'                 => $this->company->id,
                'secretary_id'               => $secretary->id,
                'teacher_id'                 => $teacher->id,
                'rector'                     => $rector->id,
                'contract_status_id'         => $status->id,
                'type_framework_agreement_id' => $type->id,

                'contact_employee_id'        => $this->employee->id, // vínculo que bloquea
                'representative_employee_id' => $repEmployee->id,
            ]);

        $response = $this->delete(route('employees.destroy', $this->employee));

        // Si capturás la FK en el controller y redirigís con error:
    

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('employees', ['id' => $this->employee->id]);
    }
}
