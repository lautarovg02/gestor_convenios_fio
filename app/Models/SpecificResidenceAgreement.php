<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificResidenceAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'internship_initial_date',
        'task',
        'signing_date',
        'contract_id',
        'student_id',
        'file',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function SpecificResidenceAgreements()
    {
        return $this->hasMany(ReportSpecificResidenceAgreement::class);
    }

      public function SpecificResidenceAgreementContracts()
    {
        return $this->hasMany(ReportSpecificResidenceAgreement::class);
    }

    
}
