<?php
// tests/Feature/Validation/AgreementFormsTest.php
namespace Tests\Feature\Validation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AgreementFormsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Chequea que los formularios muestren los mensajes de error definidos en los Requests
     *
     * @dataProvider invalidFormsProvider
     */
    public function test_form_shows_request_messages_when_invalid(
        string $routeName,
        array $invalidPayload,
        array $errorFields
    ): void {
        $response = $this->post(route($routeName), $invalidPayload);

        $response->assertSessionHasErrors($errorFields);
    }



    public static function invalidFormsProvider(): array
    {
        return [
            // === Convenio Marco ===
            'Framework Agreement invalid' => [
                'routeName' => 'frameworkAgreement.store',
                'invalidPayload' => [
                    // Enviamos poco y mal a propósito
                    'contact_nombre' => '',
                    'contact_email'  => 'abc',
                    'lugar_firma'    => '',
                    'fecha_firma'    => 'ayer', // no date
                ],
                'errorFields' => ['contact_nombre','contact_email','lugar_firma','fecha_firma'],
            ],
    
            // === Convenio Específico ===
            'Specific Agreement invalid' => [
                'routeName' => 'specificAgreement.store',
                'invalidPayload' => [
                    'contract_id'   => null,
                    'objetivo'      => '',
                    'contact_email' => 'abc',
                    'fecha_firma'   => '??',
                ],
                'errorFields' => ['contract_id','objetivo','contact_email','fecha_firma'],
            ],
    
            // === Convenio Marco de Pasantía ===
            'Framework Internship invalid' => [
                'routeName' => 'frameworkInternshipAgreement.store',
                'invalidPayload' => [
                    'contact_email'     => 'abc',
                    'contraparte_cuit'  => '123',       // digits:11
                    'ambito'            => 'otro',      // in:nacional,internacional
                ],
                'errorFields' => ['contact_email','contraparte_cuit','ambito'],
            ],
    
            // === Convenio Marco de Residencia ===
            'Framework Residence invalid' => [
                'routeName' => 'frameworkResidenceAgreement.store',
                'invalidPayload' => [
                    'contact_dni'    => '',   // required
                    'contact_email'  => 'abc',
                    'lugar_firma'    => '',   // required
                    'fecha_firma'    => 'ayer',
                ],
                'errorFields' => ['contact_dni','contact_email','lugar_firma','fecha_firma'],
            ],
    
            // === Convenio Específico de Residencia ===
            'Specific Residence invalid' => [
                'routeName' => 'specificResidenceAgreement.store',
                'invalidPayload' => [
                    'dniStudent'   => '',      // required
                    'studentEmail' => 'abc',   // email
                    'fecha_firma'  => 'ayer',  // date
                ],
                'errorFields' => ['dniStudent','studentEmail','fecha_firma'],
            ],
    
            // === Acuerdo Individual de Pasantía ===
            'Individual Internship invalid' => [
                'routeName' => 'individual-internship-agreements.store',
                'invalidPayload' => [
                    'student_id'     => null,       // required|exists
                    'student_email'  => 'abc',      // email
                    'fecha_convenio' => 'hoy!',     // date
                ],
                'errorFields' => ['student_id','student_email','fecha_convenio'],
            ],
        ];
    }
    

  
}
