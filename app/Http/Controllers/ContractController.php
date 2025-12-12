<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
   

    public function downloadDocument(Contract $contract, string $type)
    {
        // Mapeo del tipo de documento (de la URL) a la columna de la DB
        $columnMap = [
            'afip'          => 'url_certificate_afip',
            'estatuto'      => 'url_statute',
            'autoridades'   => 'url_assignment_authorities',
        ];

        // 1. Verificar que el tipo solicitado sea válido
        if (!isset($columnMap[$type])) {
            abort(404, 'Tipo de documento no válido.');
        }

        $dbColumn = $columnMap[$type];
        $filePath = $contract->{$dbColumn};

        // 2. Verificar si el contrato tiene la ruta del archivo
        if (!$filePath) {
            // No hay archivo adjunto para este tipo
            return redirect()->back()->with('error', 'No hay documento adjunto para ' . $type);
        }

        // 3. Verificar si el archivo existe en el disco
        // Laravel busca automáticamente en el disco 'local' (storage/app) por defecto
        if (!Storage::exists($filePath)) {
            // El registro existe en la DB, pero el archivo físico no está
            return redirect()->back()->with('error', 'El archivo no fue encontrado en el servidor.');
        }

        // 4. Forzar la descarga
        // El método download() es la forma segura y protegida de servir archivos privados
        $fileName = basename($filePath); // Extrae solo el nombre del archivo
        
        return Storage::download($filePath, $fileName);
    }
}