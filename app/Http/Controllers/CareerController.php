<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCareerRequest;
use App\Models\Teacher;
use App\Models\Career;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @App\Models\Career
     * @Illuminate\Http\Request
     * @App\Models\Department
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $departmentId = $request->input('department'); //filtro por departamento
        $sort = $request->input('sort', 'name'); // Valor por defecto
        $direction = $request->input('direction', 'asc'); // Valor por defecto

        try {
            // Llama al nuevo método en el modelo
            $careers = Career::searchAndSort($search, $departmentId , $sort, $direction)->paginate(9);

            $departments = Department::orderBy('name', 'ASC')->get();

             // Mensaje de vacío si no hay carreras
            if ($careers->isEmpty()) {
                return view('careers.index')->with(['careers' => $careers, 'departments' => $departments, 'noResults' => true]);
            }

            //$departments = Department::orderBy('name', 'ASC')->get();
            return view('careers.index', compact('careers', 'departments'));
        } catch (\Exception $e) {
            $departments = Department::orderBy('name', 'ASC')->get();
            return redirect()->route('careers.index')->with(['error' => 'Error al cargar las carreras. Inténtalo nuevamente.'])->with(['departments' => $departments]);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @App\Models\Teacher;
     * @App\Models\Department;
     */
    public function create(): View
    {
        $coordinators = Teacher::getTeachersWithoutRoles()->orderBy('name', 'ASC')->get();
        $departments = Department::orderBy('name', 'ASC')->get();
        return view('careers.create', compact('departments', 'coordinators'));
    }

    /**
     * Store a newly created resource in storage.
     * @App\Models\Career
     */
    public function store(Request $request): RedirectResponse
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
     * @dairagalceran
     */
    public function edit(Career $career): View
    {
        $teachersWithoutRol = Teacher::getTeachersWithoutRoles()->orderBy('lastname' , 'ASC')->get();
        $departments = Department::orderBy('name' , 'ASC')->get();
        return view('careers.edit', compact('career' , 'departments', 'teachersWithoutRol'));
    }

    /**
     * Update a career with the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  Career $career
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCareerRequest $request, Career $career)//: RedirectResponse
    {
        try{
            $validatedData = $request->validated();
            $career->update($validatedData);
            return redirect()->route('careers.index')->with('success' , 'Carrera editada con éxito.');

        }catch(\Exception $e){

            return redirect()->route('careers.edit' , $career->id)->withErrors(['error' , $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career): RedirectResponse
    {
        try {
            $career->delete();

            return redirect()->route('careers.index')->with('success', 'Carrera eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('careers.index')->with('error', 'Error al eliminar la carrera: ' . $e->getMessage());
        }
    }
}
