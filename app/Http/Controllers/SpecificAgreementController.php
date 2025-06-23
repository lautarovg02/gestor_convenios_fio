<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSpecificAgreementRequest;

class SpecificAgreementController extends Controller
{
    // Mostrar el formulario
    public function create()
    {
        //$provincias = $this->getProvinciasDesdeAPI();
        return view('specificAgreement.create');
    }

    // Guardar los datos del formulario
    public function store()
    {
/*        $validated = $request->validated();

        // Aquí iría tu lógica para guardar el convenio, generar Word, etc.
        // Por ahora, simplemente mostramos los datos para verificar

        return back()->with('success', 'Convenio Específico creado correctamente.');*/
    }

    private function getProvinciasDesdeAPI()
    {
       /* $response = \Http::get('https://apis.datos.gob.ar/georef/api/provincias');
        return $response->json()['provincias'] ?? [];*/
    }
}
