<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secretary extends Model
{
    use HasFactory;

    protected $fillable = ['user_name', 'password', 'email'];

    // Define la relación uno a muchos con SecretaryPhone
    public function phones()
    {
        return $this->hasMany(SecretaryPhone::class);
    }

    //Relación 1:n atributo multivaluado en la tabla Contract
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'secretary_id');
    }
}
