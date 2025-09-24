<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secretary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function phones()
    {
        return $this->hasMany(SecretaryPhone::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'secretary_id');
    }
}
