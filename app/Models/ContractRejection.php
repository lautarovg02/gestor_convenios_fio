<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRejection extends Model
{
    use HasFactory;

    protected $fillable = ['agreement_id', 'agreement_type', 'justification', 'user_id'];

    public function agreement()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
