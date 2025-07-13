<?php

namespace Tests\Feature\FrameworkAgreement;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FrameworkAgreementValidateFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function campos_requeridos_fallan_si_estan_vacios()
    {
        $response = $this->post(route('frameworkAgreement.store'), []);

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
            'contact_cargo',
            'razon_social',
            'ambito',
            'cuit_prefijo',
            'cuit_dni',
            'cuit_dv',
            'rubro',
            'entidad',
            'dedicacion',
            'titular',
            'confidencialidad',
            'calle',
            'nro_calle',
            'codigo_postal',
            'localidad',
            'provincia',
            'pais',
            'firma_nombre',
            'firma_apellido',
            'firma_dni',
            'firma_cargo',
            'firma_email',
            'firma_empresa_razon_social',
            'lugar_firma',
            'fecha_firma',
        ]);
    }

    /** @test */

public function formulario_se_acepta_con_datos_validos()
{
    // 1) Crear una secretaria
    \App\Models\Secretary::factory()->create();
    \App\Models\City::factory()->create();

    // 2) Crear un rector (Teacher con is_rector = true)
    \App\Models\Teacher::factory()->create([
        'is_rector' => true,
    ]);

    // 3) Payload con todos los campos obligatorios
    $payload = [
        // Representante contacto
        'contact_nombre'   => 'Laura',
        'contact_apellido' => 'González',
        'cuil_prefijo'     => '20',
        'cuil_dni'         => '12345678',
        'cuil_dv'          => '3',
        'contact_dni'      => '12345678',
        'contact_celular'  => '1155544433',
        'contact_email'    => 'laura@example.com',
        'contact_empresa'  => 'TechCorp',
        'contact_cargo'    => 'Coordinadora',

        // Contraparte
        'razon_social'     => 'TechCorp',
        'ambito'           => 'nacional',
        'cuit_prefijo'     => '30',
        'cuit_dni'         => '87654321',
        'cuit_dv'          => '1',
        'rubro'            => 'Tecnología',
        'entidad'          => 'privada',
        'dedicacion'       => 'Desarrollo de software',
        'titular'          => 'Ing. Pérez',
        'confidencialidad' => 'si',

        // Dirección
        'calle'         => 'Mitre',
        'nro_calle'     => '123',
        'codigo_postal' => '7000',
        'localidad'     => 'Vanceport',
        'provincia'     => 'Bs. As.',
        'pais'          => 'Argentina',

        // Representante firma
        'firma_nombre'               => 'José',
        'firma_apellido'             => 'López',
        'firma_dni'                  => '22334455',
        'firma_cargo'                => 'Gerente',
        'firma_email'                => 'jose.lopez@example.com',
        'firma_empresa_razon_social' => 'TechCorp',

        // Lugar y fecha
        'lugar_firma' => 'Tandil',
        'fecha_firma' => '2025-06-22',
    ];

    // 4) Ejecutar la petición
    $response = $this->post(route('frameworkAgreement.store'), $payload);

    // 5) Aserciones
    $response->assertSessionDoesntHaveErrors();
    $response->assertRedirect();  
    $response->assertSessionHas('success', 'Convenio creado correctamente');
}


    /** @test */
    public function rechaza_adjuntos_con_formatos_invalidos()
    {
        // archivo .exe no permitido
        $badFile = UploadedFile::fake()->createWithContent('malware.exe', 'virus');

        $payload = array_merge($this->validBasePayload(), [
            'doc_afip' => $badFile,
        ]);

        $response = $this->post(route('frameworkAgreement.store'), $payload);

        $response->assertSessionHasErrors(['doc_afip']);
    }

    /** @test */
    
     
  /*        public function acepta_adjuntos_pdf_y_jpg()
    {
        $pdf = UploadedFile::fake()->create('file.pdf', 100, 'application/pdf');
        $jpg = UploadedFile::fake()->image('image.jpg');

        $payload = array_merge($this->validBasePayload(), [
            'doc_afip'       => $pdf,
            'doc_estatuto'   => $jpg,
            'doc_autoridades'=> $jpg,
        ]);

        $response = $this->post(route('frameworkAgreement.store'), $payload);

        $response->assertSessionDoesntHaveErrors([
            'doc_afip',
            'doc_estatuto',
            'doc_autoridades',
        ]);
    } */
         

    /**
     * Payload base con todos los campos obligatorios válidos.
     */
    private function validBasePayload(): array
    {
        return [
            // Representante contacto
            'contact_nombre'   => 'Laura',
            'contact_apellido' => 'González',
            'cuil_prefijo'     => '20',
            'cuil_dni'         => '12345678',
            'cuil_dv'          => '3',
            'contact_dni'      => '12345678',
            'contact_celular'  => '1155544433',
            'contact_email'    => 'laura@example.com',
            'contact_empresa'  => 'TechCorp',
            'contact_cargo'    => 'Coordinadora',

            // Contraparte
            'razon_social'     => 'TechCorp',
            'ambito'           => 'nacional',
            'cuit_prefijo'     => '30',
            'cuit_dni'         => '87654321',
            'cuit_dv'          => '1',
            'rubro'            => 'Tecnología',
            'entidad'          => 'privada',
            'dedicacion'       => 'Desarrollo de software',
            'titular'          => 'Ing. Pérez',
            'confidencialidad' => 'si',

            // Dirección
            'calle'           => 'Mitre',
            'nro_calle'       => '123',
            'codigo_postal'   => '7000',
            'localidad'       => 'Tandil',
            'provincia'       => 'Bs. As.',
            'pais'            => 'Argentina',

            // Representante firma
            'firma_nombre'             => 'José',
            'firma_apellido'           => 'López',
            'firma_dni'                => '22334455',
            'firma_cargo'              => 'Gerente',
            'firma_email'              => 'jose.lopez@example.com',
            'firma_empresa_razon_social' => 'TechCorp',

            // Lugar y fecha
            'lugar_firma' => 'Tandil',
            'fecha_firma' => '2025-06-22',
        ];
    }
}
