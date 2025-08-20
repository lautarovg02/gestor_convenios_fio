<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct( StudentService $studentService )
    {
        $this->studentService = $studentService;
    }

    public function getStudentByDni($dni)
    {
        $student = $this->studentService->findStudentByDni($dni);

        if ($student) {
            return response()->json($student);
        } else {
            return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
    }

    //
}
