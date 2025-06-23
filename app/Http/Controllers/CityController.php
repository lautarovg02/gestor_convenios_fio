<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCityRequest;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CityController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name', 'ASC')->get();
        return view('cities.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request)
    {

        City::create($request->validated());

        return redirect()->route('companies.create')
            ->with('success', 'Ciudad agregada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


public function getCiudades(Request $request)
{
    $provincia = $request->input('provincia');

    $response = Http::get('https://apis.datos.gob.ar/georef/api/localidades', [
        'provincia' => $provincia,
        'campos' => 'nombre',  // agrego código postal acá
        'max' => 1000
    ]);

    $ciudades = $response->json()['localidades'] ?? [];

    return response()->json($ciudades);
}


}
