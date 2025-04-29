<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Career;
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

            $departments = Department::orderBy('name' , 'ASC')->paginate(10);

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
    public function show(Department $department): View
    {
        $careersBelongsToDepartment = $department->careers;
        return view('departments.show' , compact('department' , 'careersBelongsToDepartment'));
    }

    /**
     * Show the form for editing the specified resource.
     * @dairagalceran
     */
    public function edit(Department $department): View
    {

        $teachersWithoutRol = Teacher::getTeachersWithoutRoles()->orderBy('lastname', 'ASC')->get();

        return view('departments.edit', compact('department' ,  'teachersWithoutRol'));
    }

    /**
     * Update the specified resource in storage.
     * @dairagalceran
     */
    public function update(StoreDepartmentRequest $request, Department $department) : RedirectResponse
    {
        try{

            $validatedData = $request->validated();
            $department->update($validatedData);
            return redirect()->route('departments.index')->with('success' , 'Departamento editado exitosamente');

        }catch(\Exception $e){

            return redirect()->route('departments.edit', $department->id)->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        try {
            // Verificar si el departamento tiene carreras asociadas
            $careersCount = Career::where('department_id', $department->id)->count();

            if ($careersCount > 0) {
                return redirect()->route('departments.index')
                    ->with('error', 'No se puede eliminar el departamento porque tiene carreras asociadas.');
            }

            // Eliminar el departamento
            $department->delete();

            return redirect()->route('departments.index')
                ->with('success', 'Departamento eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('departments.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el departamento: ' . $e->getMessage());
        }
    }
}
