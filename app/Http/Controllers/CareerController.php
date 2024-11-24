<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Career;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Teacher;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @App\Models\Career;
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name'); // Valor por defecto
        $direction = $request->input('direction', 'asc'); // Valor por defecto

        // Asegúrate de que la dirección sea válida
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        try {
            // Aplica la búsqueda y la ordenación
            $careers = Career::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
                ->orderBy($sort, $direction) // Ordena según el sort y la dirección
                ->paginate(10);

            // Mensaje de vacío si no hay carreras
            if ($careers->isEmpty()) {
                return view('careers.index')->with(['careers' => $careers, 'noResults' => true]);
            }

            return view('careers.index', compact('careers'));
        } catch (\Exception $e) {
            // Guardar el error en la sesión
            return redirect()->route('careers.index')->with('error', 'Error al cargar las carreras. Inténtalo nuevamente.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coordinators = Teacher::getTeachersWithoutRoles()->orderBy('name', 'ASC')->get();
        $departments = Department::orderBy('name', 'ASC')->get();
        return view('careers.create', compact('departments', 'coordinators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Establecer reglas de validación
        $request->validate([
            'name' => 'required|string|max:255', // Campo 'name' no debe estar vacío
            'coordinator_id' => 'required|exists:teachers,id', // Campo 'coordinator_id' debe seleccionarse
            'department_id' => 'required|exists:departments,id', // Campo 'department_id' debe seleccionarse
        ]);
        $exists = Career::where('name', $request->input('name'))
            ->where('department_id', $request->input('department_id'))->exists();

        if ($exists) { // si la carrera ya existe
            return redirect()->back()
                ->withErrors(['name' => 'Ya existe una carrera con el mismo nombre.'])
                ->withInput();
        } else { //si la carrera no existe se crea
            $career = Career::create([
                'name' => $request->input('name'),
                'coordinator_id' => $request->input('coordinator_id'),
                'department_id' => $request->input('department_id'),
            ]);
        }

        // Redirigir a la vista
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
