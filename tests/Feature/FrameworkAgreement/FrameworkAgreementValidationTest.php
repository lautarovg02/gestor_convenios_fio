<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameworkAgreementValidationTest extends TestCase
{
    /** @test */
    public function valida_que_los_campos_obligatorios_esten_presentes()
    {
        $response = $this->post('/frameworkAgreement', []); // sin datos

        $response->assertSessionHasErrors([
            'contact_nombre',
            'contact_apellido',
            'contact_celular',
            'contact_email',
            'contact_empresa',
        ]);
    }

    /** @test */
    public function acepta_formulario_si_los_datos_son_validos()
    {
        $response = $this->post('/frameworkAgreement', [
            'contact_nombre' => 'Juan',
            'contact_apellido' => 'Pérez',
            'contact_celular' => '1122334455',
            'contact_email' => 'juan@example.com',
            'contact_empresa' => 'Empresa S.A.',
        ]);

        $response->assertSessionDoesntHaveErrors(); // pasa validación
    }
}
