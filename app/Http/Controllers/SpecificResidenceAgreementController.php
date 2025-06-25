<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CompanyService;
use App\Http\Requests\StoreSpecificResidenceAgreement;
use App\Models\SpecificResidenceAgreement;
use App\Services\StudentService;
use Carbon\Carbon;

class SpecificResidenceAgreementController extends Controller
{
    protected $companyService;
    protected $studentService;
    
    
    public function __construct(CompanyService $companyService, StudentService $studentService){
        $this->companyService = $companyService;
        $this->studentService = $studentService;
    }

    public function create(){

        $companies = $this->companyService->getCompaniesByTypeFrameworkAgreement('Convenio Marco de Residencia');
        
        return view("specificResidenceAgreement.create", compact('companies'));
    }

    public function store(StoreSpecificResidenceAgreement  $request){
        
        $data = $request->validated();

        $student = $this->studentService->findOrCreateByDni([
            'name' => $data['studentName'],
            'last_name' => $data['studentLastName'],
            'email' => $data['studentEmail'],
            'phone' => $data['studentCelular'],
            'cuil' => $data['studentCuil'] ?? null,
            'dni' => $data['dniStudent'],
            'carrera' => $data['studentCarrer'],
        ]);


        $agreement = SpecificResidenceAgreement::create([
            'title' => $data['agreementName'],
            'task' => $data['tasks'],
            'signing_date' => $data['fecha_firma'] ?? null,
            'student_id' => $student->id,
            'contract_id' => $data['contract_id'],
            'file' => $data['file'] ?? null,
            'internship_initial_date' => Carbon::now(),      //se establece cuando lo acepta secretaria
        ]);

        dd($agreement);
    
    }
}
