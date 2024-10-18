<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretaryPhone extends Model
{
    use HasFactory;

    protected $table = 'secretaries_phones';
    protected $fillable = ['phone_number', 'secretary_id'];

    // Define la relación inversa con Secretary
    public function secretary()
    {
        return $this->belongsTo(Secretary::class);
    }
}
