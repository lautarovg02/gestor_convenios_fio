<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        try{

            $departments = Department::orderBy('name' , 'ASC')->paginate(9);

            $noResults = $departments->isEmpty();

            return view('departments.index')->with(['departments' => $departments, 'noResults' => $noResults]);


        }catch(\Exception $e){
            return redirect()->route('departments.index')->with('error', 'Error al cargar departamentos. Inténtalo nuevamente.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
     */
    public function edit(Department $department)
    {
        $department = Department::find($department->id);

        return view('departments.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDepartmentRequest $request, Department $department) : RedirectResponse
    {
        dd($request);

        try{
            $exists = Department::where('director_id', $request->teacher->id)->first();

            dd($department);
            $department->update($request->validated());

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
