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

// Scope para la búsqueda
public function scopeSearch($query, $searchTerm)
{
    if ($searchTerm) {
        $query->where('denomination', 'LIKE', "%{$searchTerm}%")
              ->orWhere('company_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('cuit', 'LIKE', "%{$searchTerm}%")
              ->orWhere('sector', 'LIKE', "%{$searchTerm}%")
              ->orWhere('entity', 'LIKE', "%{$searchTerm}%")
              ->orWhere('company_category', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('city', function ($query) use ($searchTerm) {
                  $query->where('name', 'LIKE', "%{$searchTerm}%");
              });
    }
    return $query;
}

}
