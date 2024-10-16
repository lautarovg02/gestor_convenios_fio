<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretary extends Model
{
    use HasFactory;

    protected $fillable = ['user_name', 'password', 'email'];

    // Define la relación uno a muchos con SecretaryPhone
    public function phones()
    {
        return $this->hasMany(SecretaryPhone::class);
    }
}
