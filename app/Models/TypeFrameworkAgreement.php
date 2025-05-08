<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeFrameworkAgreement extends Model
{
    use HasFactory;
    protected $fillable = ['type'];

     //Relación 1:n atributo multivaluado en la tabla Contract
     public function contracts(): HasMany
     {
         return $this->hasMany(Contract::class, 'type_framework_agreement_id');
     }
}
