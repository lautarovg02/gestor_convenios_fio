<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}