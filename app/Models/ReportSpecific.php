<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSpecific extends Model
{
    use HasFactory;
    protected $fillable = [
        'specific_id',
        'specific_contract_id',
        'upload_date',
        'type',
        'url_report',
        'type_report_id',
        'file',
    ];

    public function specific()
    {
        return $this->belongsTo(Specific::class, 'specific_id');
    }

      public function specificContract()
    {
        return $this->belongsTo(Specific::class, 'specific_contract_id');
    }

    public function typeReport()
    {
        return $this->belongsTo(Type_Report::class, 'type_report_id');
    }
}
