<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Specific;

class StoreSpecificAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_specific_agreement()
    {
        // Simular almacenamiento de archivos
        Storage::fake('public');


        $contract = Contract::factory()->create();
        
        // Datos para la solicitud
        $data = [
            'contract_id' => $contract->id,
            'fecha_firma' => Carbon::now()->toDateString(),
            'objetivo' => 'Objetivo del convenio',
            'compromisos' => 'Compromisos importantes',
            'responsable_control_company' => 'Responsable Control Company',
            'responsable_control_fio' => 'Responsable Control FIO',
            'student_id' => 1, // ID del estudiante, si aplica
            // Incluye el archivo
            'file' => UploadedFile::fake()->create('documento.pdf', 1000, 'application/pdf'),
        ];

        // Realizar la petición POST
        $response = $this->post(route('specific.store'), $data);

        // Verificar respuesta y vista
        $response->assertStatus(200);
        $response->assertViewIs('path.to.view'); // reemplaza con la vista correcta que devuelve

        // Verificar que se generó el archivo
        Storage::disk('public')->assertExists('specific_files/' . basename($response->json('file')));

        // Verificar en la base de datos
        $this->assertDatabaseHas('specifics', [
            'contract_id' => $contract->id,
            'objective' => 'Objetivo del convenio',
            'commitment_parties' => 'Compromisos importantes',
            'file' => 'specific_files/' . basename($response->json('file')),
        ]);
    }
}