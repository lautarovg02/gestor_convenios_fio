<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'province_id'];


    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
