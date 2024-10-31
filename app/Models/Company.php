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
        
            $terms = explode(' ', $searchTerm);

            foreach ($terms as $term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('denomination', 'LIKE', "%{$term}%")
                         ->orWhere('company_name', 'LIKE', "%{$term}%")
                         ->orWhere('cuit', 'LIKE', "%{$term}%")
                         ->orWhere('sector', 'LIKE', "%{$term}%")
                         ->orWhere('entity', 'LIKE', "%{$term}%")
                         ->orWhere('company_category', 'LIKE', "%{$term}%")
                         ->orWhereHas('city', function ($query) use ($term) {
                             $query->where('name', 'LIKE', "%{$term}%");
                         });
                });
            }
        }
        return $query;
    }
}
