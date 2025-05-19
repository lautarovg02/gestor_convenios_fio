<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specific extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'signing_date',
        'objective',
        'commitment_parties',
        'responsable_control_company',
        'responsable_control_fio',
        'file',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    //Relación 1:n atributo multivaluado en la tabla ReportType
      public function reports(): HasMany
      {
          return $this->hasMany(report_specific::class, 'specific_id');
      }

      //Relación 0:n atributo multivaluado en la tabla ReportType
      public function reportContracts(): HasMany
      {
          return $this->hasMany(report_specific::class, 'specific_contract_id');
      }
}