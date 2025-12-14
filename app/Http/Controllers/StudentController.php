<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Models\Student;
use App\Models\Career;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

public function index(Request $request)
{
    // 1. Iniciar query
    $query = Student::orderBy('last_name')->orderBy('name');

    // 2. Aplicar Filtros
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('dni', 'like', "%{$search}%");
        });
    }

    if ($request->filled('career')) {
        $query->where('career', 'like', "%{$request->career}%");
    }
    
    if ($request->filled('city')) {
        $query->where('city', 'like', "%{$request->city}%");
    }

    // 3. Paginar
    $students = $query->paginate(10); // 10 por página

    // 4. Obtener carreras para el filtro (opcional)
    $careers = Career::orderBy('name')->get();

    return view('students.index', compact('students', 'careers'));
}

    public function create()
    {
        // Pasamos carreras por si quieres usar un select en el futuro
        $careers = Career::orderBy('name')->get(['id', 'name']);
        return view('students.create', compact('careers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'dni'        => 'required|numeric|digits_between:7,8|unique:students,dni',
            'cuil'       => 'required|string|max:15|unique:students,cuil', 
            'email'      => 'required|email|max:255|unique:students,email',
            'phone_numb' => 'nullable|string|max:20',
            'career'     => 'required|string|max:255',
            'street'     => 'required|string|max:255',
            'number'     => 'required|numeric',
            'city'       => 'required|string|max:100',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Alumno creado correctamente.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        // Pasamos carreras también aquí por si decides cambiar el input text por un select
        $careers = Career::orderBy('name')->get(['id', 'name']);
        
        return view('students.edit', compact('student', 'careers'));
    }

    public function update(Request $request, Student $student)
    {
        // Validaciones ajustadas para ignorar al usuario actual en campos únicos
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'dni'        => 'required|numeric|digits_between:7,8|unique:students,dni,' . $student->id,
            'cuil'       => 'required|string|max:15|unique:students,cuil,' . $student->id,
            'email'      => 'required|email|max:255|unique:students,email,' . $student->id,
            'phone_numb' => 'nullable|string|max:20', // Corregido de 'phone' a 'phone_numb'
            'career'     => 'required|string|max:255',
            'street'     => 'required|string|max:255',
            'number'     => 'required|numeric',
            'city'       => 'required|string|max:100',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy(Student $student)
    {
        try {
            // Desvincular relaciones si existen antes de borrar
            if (method_exists($student, 'specifics')) {
                $student->specifics()->detach();
            }
            
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'Estudiante eliminado correctamente.'); // Cambié status por success para consistencia
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo eliminar el estudiante porque tiene registros vinculados.');
        }
    }

    // --- MÉTODOS AJAX / API ---

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

    public function getStudentByDni($dni)
    {
        // Usamos el servicio inyectado
        $student = $this->studentService->findStudentByDni($dni);

        if ($student) {
            return response()->json($student);
        } else {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
    }
}