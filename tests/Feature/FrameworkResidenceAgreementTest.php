<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\CompanyEntity;
use App\Models\EmployeePhone;
use App\Models\Secretary;
use App\Models\SecretaryPhone;
use App\Models\Teacher;

class FrameworkResidenceAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_framework_residence_agreement()
    {
        $validated = [
            // Contacto
            'contact_nombre' => 'Juan',
            'contact_apellido' => 'Pérez',
            'contact_dni' => '12345678',
            'contact_cuil_prefijo' => '20',
            'contact_cuil_dni' => '12345678',
            'contact_cuil_dv' => '3',
            'contact_celular' => '1123456789',
            'contact_email' => 'juan@example.com',
            'contact_empresa' => 'Empresa SA',
            'contact_cargo' => 'Responsable',

            // Firma
            'firma_nombre' => 'Ana',
            'firma_apellido' => 'López',
            'firma_dni' => '87654321',
            'firma_empresa_razon_social' => 'Empresa SA',
            'firma_cargo' => 'Representante Legal',
            'firma_email' => 'ana@example.com',

            // Contraparte
            'razon_social' => 'Empresa SA',
            'ambito' => 'nacional',
            'contraparte_cuit_prefijo' => '30',
            'contraparte_cuit_dni' => '12345678',
            'contraparte_cuit_dv' => '5',
            'contraparte_rubro' => 'Tecnología',
            'titular' => 'Carlos Pérez',
            'confidencialidad' => 'si',

            // Dirección
            'pais' => 'Argentina',
            'provincia' => 'Buenos Aires',
            'localidad' => 'Tandil',
            'codigo_postal' => '7000',
            'calle' => 'Falsa',
            'nro_calle' => '123',

            // Lugar y fecha
            'lugar_firma' => 'Tandil',
            'fecha_firma' => Carbon::now()->toDateString(),
        ];

        // Ejecutar POST al endpoint que ejecuta el método store del controller
        $response = $this->post(route('frameworkResidenceAgreement.store'), $validated);

        // Verificar que responde con status 200 OK (sin redirección)
        $response->assertStatus(200);

        // Verificar que devuelve la vista correcta
        $response->assertViewIs('frameworkInternshipAgreement.creationSuccessful');

        // Verificar que la vista tiene las variables necesarias
        $response->assertViewHasAll(['relativePath', 'nombreArchivo']);

        $company = Company::where('denomination', 'Empresa SA')->first();


        // Verificar que el contrato se creó en la base de datos con la razon_social
        $this->assertDatabaseHas('companies', [
            'denomination' => $validated['razon_social'],
        ]);

        // Verificar que el contrato se creó en la base de datos con la razon_social
        $this->assertDatabaseHas('contracts', [
            'company_id' => $company->id,
        ]);

        $this->assertDatabaseHas('employees', [
            'name' => $validated['contact_nombre'],
        ]);

        $this->assertDatabaseHas('employees', [
            'name' => $validated['firma_nombre'],
        ]);

        $this->assertDatabaseHas('cities', [
            'name' => $validated['localidad'],
        ]);

        $this->assertDatabaseHas('provinces', [
            'name' => $validated['provincia'],
        ]);

        $this->assertDatabaseHas('type_framework_agreements', [
            'type' => 'Convenio Marco de Residencia',
        ]);

        $this->assertDatabaseHas('contract_statuses', [
            'status' => 'SEVyT (Estado de aprobación/Análisis)',
        ]);

        $this->assertNotNull(Teacher::first());

        $this->assertNotNull(Secretary::first());

        $this->assertNotNull(EmployeePhone::first());
        $this->assertNotNull(CompanyEntity::first());
        
    }
}
