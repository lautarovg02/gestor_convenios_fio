<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Teacher;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('careers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coordinators = Teacher::orderBy('name', 'ASC')->get();
        $departaments = Department::orderBy('name', 'ASC')->get();
        return view('careers.create', compact('departaments', 'coordinators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coordinator_id' => 'required|exists:teachers,id',
            'departament_id' => 'required|exists:departments,id',
        ], [
            'name.required' => 'El nombre de la carrera es obligatorio.',
            'coordinator_id.required' => 'Debes seleccionar un coordinador.',
            'coordinator_id.exists' => 'El coordinador seleccionado no es válido.',
            'departament_id.required' => 'Debes seleccionar un departamento.',
            'departament_id.exists' => 'El departamento seleccionado no es válido.',
        ]);

        $exists = Career::where('coordinator_id', $request->coordinator_id)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['coordinator_id' => 'El coordinador ya está asignado a otra carrera.'])->withInput();
        } else {
            // Proceder a insertar el nuevo registro
            $career = Career::create([
                'name' => $request->input('name'),
                'coordinator_id' => $request->input('coordinator_id'),
                'department_id' => $request->input('departament_id'),
            ]);
        }
        return redirect()->route('careers.index')->with('success', 'Carrera creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Career $career)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Career $career)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career)
    {
        //
    }
}
