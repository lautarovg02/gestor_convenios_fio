<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Country; 
use App\Models\City;
    


class Province extends Model
{
    use HasFactory;
    protected $table = 'provinces'; // o 'provinces' si es plural

    protected $fillable = ['name'];
    // Una provincia pertenece a un país
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Una provincia tiene muchas ciudades
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
