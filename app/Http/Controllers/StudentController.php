<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Career;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Muestra todos los alumnos
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $careers = Career::orderBy('name')->get(['id', 'name']);
        return view('students.create', compact('careers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dni' => 'required|numeric|digits_between:7,8',
            'cuil' => 'required|string|max:15',  // 🔥 Ahora obligatorio
            'email' => 'required|email|unique:students,email',
            'phone_numb' => 'nullable|numeric',
            'career' => 'required|string',
            'street' => 'required|string|max:255', // 🔥 Obligatorio
            'number' => 'required|numeric',        // 🔥 Obligatorio
            'city' => 'required|string|max:100',   // 🔥 Obligatorio
        ]);
        // Crea el alumno
        Student::create($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Alumno creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Muestra un alumno específico
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Muestra formulario de edición
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Valida los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|numeric|unique:students,dni,' . $student->id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Actualiza los datos
        $student->update($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Alumno actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Student $student)
    {
        try {
            // Si tenés relaciones en pivotes, desvinculá acá (no rompe si no existen)
            if (method_exists($student, 'specifics')) {
                $student->specifics()->detach();
            }
            $student->delete();

            return redirect()->route('students.index')
                ->with('status', 'Estudiante eliminado');
        } catch (\Throwable $e) {
            return back()->withErrors('No se pudo eliminar el estudiante.');
        }
    }

    //Busqueda de alumnos para el form del convenio indivual de pasantia u otros.
    public function search(Request $request)
    {
        $query = $request->q;

        $students = Student::where('dni', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($students);
    }
}
