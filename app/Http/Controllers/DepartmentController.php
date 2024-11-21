<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        try{

            $departments = Department::all();

            // Mensaje de vacío si no hay departamentos
            if ($departments->isEmpty()) {
                return view('departments.index')->with(['departments' => $$departments, 'noResults' => true]);
            }
            return view('departments.index' , compact('departments'));

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
