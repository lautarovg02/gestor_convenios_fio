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

    public function individualInternshipAgreements()
    {
        return $this->hasMany(ReportIndividualInternshipAgreement::class);
    }

      public function individualInternshipAgreementContracts()
    {
        return $this->hasMany(ReportIndividualInternshipAgreement::class);
    }

}
