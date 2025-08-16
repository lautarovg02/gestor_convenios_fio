<?php

namespace App\Services;

use App\Models\Student;
use App\Models\SpecificResidenceAgreement;
use App\Models\IndividualAgreement;
use App\Models\IndividualInternshipAgreement;
use App\Models\Specific;

class StudentService {

    /**
     * Busca o crea un estudiante por DNI.
     *
     * @param array $data
     * @return Student
     */

     

    public function findOrCreateByDni($data): Student

    {
        return Student::firstOrCreate(
            ['dni' => $data['dni']],
            [
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone_numb' => $data['phone'],
                'cuil' => $data['cuil'] ?? null,
                'dni' => $data['dni'],
                'carrer' => $data['career'] ?? null,
            ]
        );
    }

    public function findStudentByDni(int $dni): ?Student
    {
        return Student::where('dni', $dni)->first();
    }

    public function getAllStudents()
    {
        return Student::orderBy('last_name')->get();
    }

    public function existStudentInAgreement(int $idStudent): bool{

        $existsInSpecificAgreement = Specific::whereHas('students', function ($query) use ($idStudent) {
    $query->where('student_id', $idStudent);
})->exists();


        $existsInSpecificResidenceAgreement = SpecificResidenceAgreement::where('student_id', $idStudent)->exists();
        $existsInIndividualAgreement = IndividualInternshipAgreement::where('student_id', $idStudent)->exists();

        return $existsInSpecificAgreement || $existsInSpecificResidenceAgreement || $existsInIndividualAgreement;
    }




}