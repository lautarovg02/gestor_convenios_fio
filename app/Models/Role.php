<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole; // <--- Importante

// En lugar de "extends Model", extendemos de "SpatieRole"
class Role extends SpatieRole
{
    use HasFactory;

    // Spatie ya tiene su propio $fillable (name, guard_name), no hace falta redefinirlo.
    
    // IMPORTANTE:
    // Borramos la función public function users()
    // Spatie ya trae esa relación lista usando la tabla intermedia correcta.
}