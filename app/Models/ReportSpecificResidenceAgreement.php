<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSpecificResidenceAgreement extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'specific_residence_agreement_id',
        'specific_residence_agreement_contract_id',
        'upload_date',
        'url_report',
        'type_report_id',
        'file',
    ];

    public function specificResidentAgreement()
    {
        return $this->belongsTo(SpecificResidenceAgreement::class, 'specific_residence_agreement_id');
    }

      public function specificResidenceContract()
    {
        return $this->belongsTo(SpecificResidenceAgreement::class, 'specific_residence_agreement_contract_id');
    }

    public function specificTypeReport()
    {
        return $this->belongsTo(Type_Report::class, 'type_report_id');
    }
}
