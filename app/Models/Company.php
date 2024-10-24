<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['denomination','company_name' ,'city_id'];

// Definir la relación con el modelo City
public function city()
{
    return $this->belongsTo(City::class);
}

}
