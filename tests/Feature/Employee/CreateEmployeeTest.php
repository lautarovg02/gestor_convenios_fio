<?php

namespace Tests\Feature\Employee;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        // Creamos una empresa de prueba
        $this->company = Company::factory()->create();
    }

    /** @test */
    public function crea_un_empleado_correctamente()
    {
        $payload = [
            'name'     => 'Ana',
            'lastname' => 'Pérez',
            'dni'      => '35555555',
            'cuil'     => '27-35555555-3',        // si es opcional, igual se guarda
            'position' => 'Responsable Comercial',
            'email'    => 'ana.perez@acme.com',   // si es opcional, igual se guarda
        ];

        $response = $this->post(
            route('companies.employees.store', $this->company), // ajustá el name si es distinto
            $payload
        );

        $response->assertStatus(302); // redirección esperada tras crear

        $this->assertDatabaseHas('employees', [
            'company_id' => $this->company->id,
            'name'       => 'Ana',
            'lastname'   => 'Pérez',
            'dni'        => '35555555',
            'cuil'       => '27-35555555-3',
            'position'   => 'Responsable Comercial',
            'email'      => 'ana.perez@acme.com',
        ]);
    }

    /** @test */
    public function valida_dni_unico_y_rechaza_duplicados()
    {
        Employee::factory()->create([
            'company_id' => $this->company->id,
            'dni'        => '35555555',
        ]);

        $payload = [
            'name'     => 'Carla',
            'lastname' => 'Núñez',
            'dni'      => '35555555', // duplicado
            'position' => 'Contadora',
        ];

        $response = $this->post(
            route('companies.employees.store', $this->company),
            $payload
        );

        $response->assertSessionHasErrors(['dni']);
        $this->assertEquals(1, Employee::where('dni', '35555555')->count());
    }

    /** @test */
    public function permite_campos_opcionales_nulos_como_email_y_cuil()
    {
        $payload = [
            'name'     => 'Juan',
            'lastname' => 'Gómez',
            'dni'      => '12345678',
            'position' => 'Abogado',
            // 'email' y 'cuil' omitidos intencionalmente
        ];

        $response = $this->post(
            route('companies.employees.store', $this->company),
            $payload
        );

        $response->assertStatus(302);

        $this->assertDatabaseHas('employees', [
            'company_id' => $this->company->id,
            'dni'        => '12345678',
            'email'      => null,
            'cuil'       => null,
        ]);
    }

    /** @test */
    public function requiere_campos_obligatorios()
    {
        $response = $this->post(
            route('companies.employees.store', $this->company),
            []
        );

        $response->assertSessionHasErrors([
            'name',
            'lastname',
            'dni',
            'position',
        ]);
    }

    /** @test */
    public function valida_formato_de_email_si_se_envia()
    {
        $payload = [
            'name'     => 'Marta',
            'lastname' => 'Díaz',
            'dni'      => '22222222',
            'position' => 'Gerente',
            'email'    => 'no-es-un-email', // inválido
        ];

        $response = $this->post(
            route('companies.employees.store', $this->company),
            $payload
        );

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('employees', ['dni' => '22222222']);
    }
}
