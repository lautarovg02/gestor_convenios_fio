<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class City extends Model
{
    use HasFactory;

    protected $fillable = ['name','postal_code', 'province_id'];

    // ✅ Relación correcta: una ciudad pertenece a una provincia
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id' );
    }

    // ✅ (Opcional) Relación inversa: una ciudad puede tener muchas empresas
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}