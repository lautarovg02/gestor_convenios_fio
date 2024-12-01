<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Models\Career;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\RedirectResponse;
use PhpParser\Node\Expr\Throw_;

class TeacherController extends Controller
{
    /**
     * @lautarovg02
     * Display a listing of the teachers.
     * @dairaGalceran
     * search with scope
     */
    public function index(Request $request): View
    {
        $teachers = collect();
        $errorMessage = null; // Inicializar para evitar errores en caso de fallo
        $searchTerm = $request->input('search');

        try {
            $teachers = Teacher::getAllWithRoles()->search($searchTerm)
                ->paginate(9)
                ->appends(['search' => $searchTerm]);
        } catch (Exception $e) {
            $errorMessage = 'No se pudo recuperar la información de Docentes en este momento. Por favor, inténtelo más tarde.';
            \Log::error('Error al obtener profesores: ' . $e->getMessage());
        }

        return view('teachers.index', compact('teachers', 'searchTerm', 'errorMessage'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        try {
            Teacher::create($request->validated());
            return redirect()->route('teachers.index')->with('success', 'Docente ingresado exitosamente.');
        } catch (Exception $e) {
            \Log::error('Error al crear la empresa: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
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
        $teacher = $teacher->getAllWithRoles()->find($teacher->id);

        return view('teachers.edit', ['teacher' => $teacher]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        try {
            // Guarda el mensaje en la sesión
            $teacher->update($request->validated());
            return redirect()->route('teachers.index')->with('success', 'Docente actualizado exitosamente.');
        } catch (Exception $e) {
            \Log::error($e->getMessage());

            return redirect()->route('teachers.edit', $teacher->id)->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
