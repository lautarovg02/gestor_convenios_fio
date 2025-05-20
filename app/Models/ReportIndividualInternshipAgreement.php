<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportIndividualInternshipAgreement extends Model
{
    use HasFactory;
        protected $fillable = [
        'individual_internship_id',
        'individual_internship_contract_id',
        'upload_date',
        'url_report',
        'type_report_id',
        'file',
    ];

        public function individualInternshipAgreement()
    {
        return $this->belongsTo(IndividualInternshipAgreement::class, 'individual_internship_id');
    }

      public function individualInternshipContract()
    {
        return $this->belongsTo(IndividualInternshipAgreement::class, 'individual_internship_contract_id');
    }

    public function individualTypeReport()
    {
        return $this->belongsTo(Type_Report::class, 'type_report_id');
    }
}
