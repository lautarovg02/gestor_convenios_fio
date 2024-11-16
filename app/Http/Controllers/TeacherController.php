<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Exception;

class TeacherController extends Controller
{
    /**
      @lautarovg02
     * Display a listing of the teachers.
     */
    public function index()
    {
        $teachers = collect(); // Inicializar para evitar errores en caso de fallo
        try {

            $teachers = Teacher::getAllWithRoles()->paginate(9);
        } catch (Exception $e) {
            \Log::error('Error al obtener profesores: ' . $e->getMessage());
        }

        return view('teachers.index', compact('teachers'));
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
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
