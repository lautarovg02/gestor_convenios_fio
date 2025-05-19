<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{
    Contract,
    City,
    Province,
    Company,
    Secretary,
    Teacher,
    Employee,
    ContractStatus,
    TypeFrameworkAgreement
};

class ContractTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function contracts_table_has_expected_columns()
    {
        // Validar que la tabla Contract exista.
        $this->assertTrue(Schema::hasTable('contracts'), 'La tabla Contracts no fue creada');

        // Lista de columnas esperadas
        $expectedColumns = [
            'id',
            'signing_date',
            'url_certificate_afip',
            'url_statute',
            'url_assignment_authorities',
            'company_id',
            'secretary_id',
            'teacher_id',
            'creation_date',
            'contact_employee_id',
            'representative_employee_id',
            'rector',
            'contract_status_id',
            'type_framework_agreement_id',
            'file',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('contracts', $column),
                "Falta la columna '$column' en la tabla contracts"
            );
        }
    }
    /** @test */
    public function test_can_create_contract_with_all_relationships(): void
    {
        // Crear las dependencias necesarias, con las cuales tiene relación.
        $province = Province::factory()->create(); //necesaria para Company
        $city = City::factory()->create(['province_id' => $province->id]); //necesaria para Company
        $company = Company::factory()->create(['city_id' => $city->id]);
        $secretary = Secretary::factory()->create();
        $teacher = Teacher::factory()->create();
        $contactEmployee = Employee::factory()->create();
        $representativeEmployee = Employee::factory()->create();
        $rector = Teacher::factory()->create();
        $status = ContractStatus::factory()->create();
        $type = TypeFrameworkAgreement::factory()->create();
        $blob = random_bytes(128);

        // Crear el contrato
        $contract = Contract::create([
            'signing_date' => now(),
            'url_certificate_afip' => 'https://example.com/cert.pdf',
            'url_statute' => 'https://example.com/statute.pdf',
            'url_assignment_authorities' => 'https://example.com/authorities.pdf',
            'company_id' => $company->id,
            'secretary_id' => $secretary->id,
            'teacher_id' => $teacher->id,
            'creation_date' => now(),
            'contact_employee_id' => $contactEmployee->id,
            'representative_employee_id' => $representativeEmployee->id,
            'rector' => $rector->id,
            'contract_status_id' => $status->id,
            'type_framework_agreement_id' => $type->id,
            'file' => $blob,
        ]);
        //actualizar
        $contract->refresh();
        
        // Verificar que se creó
        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'company_id' => $company->id,
        ]);

        // Validar realaciones
        $this->assertEquals($company->id, $contract->company->id);  // company_id

        $this->assertEquals($status->id, $contract->status->id);  // contract_status_id

        $this->assertEquals($teacher->id, $contract->teacher->id);  // teacher_id

        $this->assertEquals($secretary->id, $contract->secretary->id);  // secretary_id

        $this->assertEquals($contactEmployee->id, $contract->contactEmployee->id);  // contact_employee_id

        $this->assertEquals($representativeEmployee->id, $contract->representativeEmployee->id);  // representative_employee_id

       $this->assertEquals($rector->id, $contract->rectorTeacher->id);  // correcto

        $this->assertEquals($type->id, $contract->typeFrameworkAgreement->id);  // type_framework_agreement_id

        //Validar la carga de archivos
        $this->assertNotNull($contract->file);
        $this->assertEquals($blob, $contract->file);
    }

/** @test */
//crear un contrato sin campos obligatorios (Company, secretary,etc.)
public function test_contract_fails_without_required_fields()
{
    $this->expectException(\Illuminate\Database\QueryException::class);
    Contract::create([]);
}

//Verificar que solo un Teacher con is_rector = false pueda asignarse como rector:
    /** @test */
public function test_contract_allows_teacher_without_is_rector_true_as_rector_currently()
{
    // Crear un teacher que no es rector
    $notRector = Teacher::factory()->create(['is_rector' => false]);

    $this->assertFalse($notRector->is_rector, 'Este teacher no es rector, pero será asignado igualmente.');

    // Crear las dependencias necesarias
    $province = Province::factory()->create();
    $city = City::factory()->create(['province_id' => $province->id]);
    $company = Company::factory()->create(['city_id' => $city->id]);
    $secretary = Secretary::factory()->create();
    $teacher = Teacher::factory()->create();
    $contactEmployee = Employee::factory()->create();
    $representativeEmployee = Employee::factory()->create();
    $status = ContractStatus::factory()->create();
    $type = TypeFrameworkAgreement::factory()->create();
    $blob = random_bytes(128);

    // Crear el contrato asignando como rector a un teacher que no lo es
    $contract = Contract::create([
        'signing_date' => now(),
        'url_certificate_afip' => 'https://example.com/cert.pdf',
        'url_statute' => 'https://example.com/statute.pdf',
        'url_assignment_authorities' => 'https://example.com/authorities.pdf',
        'company_id' => $company->id,
        'secretary_id' => $secretary->id,
        'teacher_id' => $teacher->id,
        'creation_date' => now(),
        'contact_employee_id' => $contactEmployee->id,
        'representative_employee_id' => $representativeEmployee->id,
        'rector' => $notRector->id, // Acá está el error
        'contract_status_id' => $status->id,
        'type_framework_agreement_id' => $type->id,
        'file' => $blob,
    ]);

    $this->assertEquals($notRector->id, $contract->rector, 'El contrato fue creado con un rector no válido (no tiene is_rector = true)');
}

/** @test */
//Test para actualizar datos
public function test_contract_can_be_updated(): void
{
    // Crear las dependencias necesarias
    $province = Province::factory()->create();
    $city = City::factory()->create(['province_id' => $province->id]); 
    $company = Company::factory()->create(['city_id' => $city->id]);
    $secretary = Secretary::factory()->create();
    $teacher = Teacher::factory()->create(); 
    $contactEmployee = Employee::factory()->create();
    $representativeEmployee = Employee::factory()->create();
    $status = ContractStatus::factory()->create();
    $type = TypeFrameworkAgreement::factory()->create();
    $blob = random_bytes(128);

    // Crear el contrato inicial
    $contract = Contract::create([
        'signing_date' => now(),
        'url_certificate_afip' => 'https://example.com/cert.pdf',
        'url_statute' => 'https://example.com/statute.pdf',
        'url_assignment_authorities' => 'https://example.com/authorities.pdf',
        'company_id' => $company->id,
        'secretary_id' => $secretary->id,
        'teacher_id' => $teacher->id,
        'creation_date' => now(),
        'contact_employee_id' => $contactEmployee->id,
        'representative_employee_id' => $representativeEmployee->id,
        'rector' => $teacher->id,
        'contract_status_id' => $status->id,
        'type_framework_agreement_id' => $type->id,
        'file' => $blob,
    ]);

    // Nuevos datos para actualizar el contrato
    $updatedData = [
        'signing_date' => now()->addDays(1),
        'url_certificate_afip' => 'https://newexample.com/cert.pdf', //cambio datos
        'url_statute' => 'https://newexample.com/statute.pdf',//cambio datos
        'url_assignment_authorities' => 'https://newexample.com/authorities.pdf',//cambio datos
        'company_id' => $company->id,
        'secretary_id' => $secretary->id,
        'teacher_id' => $teacher->id,
        'contact_employee_id' => $contactEmployee->id,
        'representative_employee_id' => $representativeEmployee->id,
        'rector' => $teacher->id, 
        'contract_status_id' => $status->id,
        'type_framework_agreement_id' => $type->id,
        'file' => random_bytes(128), //cambio datos
    ];

    // Actualizar el contrato
    $contract->update($updatedData);

    // Verificar que los cambios se hayan realizado
    $contract->refresh(); // Recargar el contrato actualizado

    // Verificar que los datos del contrato han sido actualizados
    $this->assertEquals($updatedData['signing_date'], $contract->signing_date);
    $this->assertEquals($updatedData['url_certificate_afip'], $contract->url_certificate_afip);
    $this->assertEquals($updatedData['url_statute'], $contract->url_statute);
    $this->assertEquals($updatedData['url_assignment_authorities'], $contract->url_assignment_authorities);
    $this->assertEquals($updatedData['file'], $contract->file);
    
}

/** @test */
//Verifique que la base no aplica límite
public function test_string_fields_can_exceed_max_length_if_not_restricted()
{
    $tooLong = str_repeat('x', 201);

   // Crear las dependencias necesarias
   $province = Province::factory()->create();
   $city = City::factory()->create(['province_id' => $province->id]); 
   $company = Company::factory()->create(['city_id' => $city->id]);
   $secretary = Secretary::factory()->create();
   $teacher = Teacher::factory()->create(); 
   $contactEmployee = Employee::factory()->create();
   $representativeEmployee = Employee::factory()->create();
   $status = ContractStatus::factory()->create();
   $type = TypeFrameworkAgreement::factory()->create();
   $blob = random_bytes(128);

    $contract = Contract::create([
        'signing_date' => now(),
        'url_certificate_afip' => $tooLong,
        'url_statute' => $tooLong,
        'url_assignment_authorities' => $tooLong,
        'company_id' => $company->id,
        'secretary_id' => $secretary->id,
        'teacher_id' => $teacher->id,
        'creation_date' => now(),
        'contact_employee_id' => $contactEmployee->id,
        'representative_employee_id' => $representativeEmployee->id,
        'rector' => $teacher->id,
        'contract_status_id' => $status->id,
        'type_framework_agreement_id' => $type->id,
        'file' => $blob,
    ]);

    $this->assertEquals(201, strlen($contract->url_certificate_afip));
    $this->assertEquals(201, strlen($contract->url_statute));
    $this->assertEquals(201, strlen($contract->url_assignment_authorities));
}


















}

