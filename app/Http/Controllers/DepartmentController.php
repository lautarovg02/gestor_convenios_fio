<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Redirect;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @dairagalceran
     */
    public function index(): View
    {

        try{

            $departments = Department::orderBy('name' , 'ASC')->paginate(9);

            $noResults = $departments->isEmpty();

            return view('departments.index')->with(['departments' => $departments, 'noResults' => $noResults]);

        } catch (\Exception $e) {
            return redirect()->route('departments.index')->with('error', 'Error al cargar departamentos. Inténtalo nuevamente.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View {
        //Consigue a todos los teachers sin rol
        $teachers = Teacher::getTeachersWithoutRoles()->orderBy('lastname', 'ASC')->get();

        return view('departments.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse {
        try {
            Department::create($request->validated());

            return redirect()->route('departments.index')->with('success', 'Departamento creado exitosamente');
        } catch(\Exception $e) {
            \Log::error('Error al crear el departamento: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @dairagalceran
     */
    public function edit(Department $department): View
    {

        $departments = Department::orderBy('name', 'ASC')->get();
        $teachersWithoutRol = Teacher::getTeachersWithoutRoles()->orderBy('lastname', 'ASC')->get();

        return view('departments.edit', compact('department' , 'departments', 'teachersWithoutRol'));
    }

    /**
     * Update the specified resource in storage.
     * @dairagalceran
     */
    public function update(StoreDepartmentRequest $request, Department $department) : RedirectResponse
    {
        try{

            $validatedData = $request->validated();

            // Si seleccionó "Otro tipo", usa el valor del input de texto
            if ($request->name === 'other') {
                $validatedData['name'] = $request->input('other_entity_input');
            }

            $department->update($validatedData);

            return redirect()->route('departments.index')->with('success' , 'Departamento creado exitosamente');

        }catch(\Exception $e){
            return redirect()->route('departments.edit', $department->id)->withErrors(['error' => $e->getMessage()]);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
