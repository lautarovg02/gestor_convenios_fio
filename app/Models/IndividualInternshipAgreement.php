<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualInternshipAgreement extends Model
{
    use HasFactory;
    protected $fillable = [
        'months_quantity',
        'task',
        'internship_initial_date',
        'assignment',
        'area',
        'signing_date',
        'contract_id',
        'contract_status_id',
        'student_id',
        'tutor_teacher_id',
        'docente_teacher_id',
        'file',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function status()
    {
        return $this->belongsTo(ContractStatus::class, 'contract_status_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /** Tutor de la empresa (docente FIO asignado como tutor) */
    public function tutor()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'tutor_teacher_id');
    }

    /** Docente responsable FIO */
    public function docenteResponsable()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'docente_teacher_id');
    }

    public function individualInternshipAgreements()
    {
        return $this->hasMany(ReportIndividualInternshipAgreement::class);
    }

      public function individualInternshipAgreementContracts()
    {
        return $this->hasMany(ReportIndividualInternshipAgreement::class);
    }

}
