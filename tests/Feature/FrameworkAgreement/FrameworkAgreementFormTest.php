<?php

namespace Tests\Feature\FrameworkAgreement;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class FrameworkAgreementFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function valida_que_los_campos_obligatorios_esten_presentes()
    {
        $response = $this->post('/frameworkAgreement', []); // ruta de prueba, ajustar si es distinta

        $response->assertSessionHasErrors([
            'contact_nombre',
            'contact_apellido',
            'cuil_prefijo',
            'cuil_dni',
            'cuil_dv',
            'contact_dni',
            'contact_celular',
            'contact_email',
            'contact_empresa',
            'razon_social',
            'cuit_prefijo',
            'cuit_dni',
            'cuit_dv',
            'firma_email',
        ]);
    }

    /** @test */
    public function acepta_formulario_si_los_datos_son_validos()
    {
        $response = $this->post('/frameworkAgreement', [
            // Representante contacto
            'contact_nombre' => 'Ana',
            'contact_apellido' => 'Pérez',
            'cuil_prefijo' => '20',
            'cuil_dni' => '12345678',
            'cuil_dv' => '9',
            'contact_dni' => '12345678',
            'contact_celular' => '1122334455',
            'contact_email' => 'ana@example.com',
            'contact_empresa' => 'Empresa S.A.',

            // Contraparte
            'razon_social' => 'Empresa S.A.',
            'cuit_prefijo' => '30',
            'cuit_dni' => '12345678',
            'cuit_dv' => '9',

            // Firma
            'firma_email' => 'firma@example.com',
        ]);

        $response->assertSessionDoesntHaveErrors();
    }

 








}
