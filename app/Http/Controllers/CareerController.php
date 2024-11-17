<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Department;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Teacher;
use Faker\Core\Coordinates;


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
        // $coordinators = Teacher::orderBy('name', 'ASC')->get();
        $coordinators = Teacher::getTeachersWithoutRoles()->orderBy('name', 'ASC')->get();

        $departaments = Department::orderBy('name', 'ASC')->get();
        return view('careers.create', compact('departaments', 'coordinators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $exists = Career::where('name',$request->input('name'))
                    ->where('departament_id', $request->input('departament_id'))->exists();
        if(!$exists){
            $career = Career::create([
                'name' => $request->input('name'),
                'coordinator_id' => $request->input('coordinator_id'),
                'department_id' => $request->input('departament_id'),
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
