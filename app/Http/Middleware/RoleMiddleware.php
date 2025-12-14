<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // 1. Verificar si está logueado
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Convertir la cadena de roles (ej: "admin,editor") en un array
        $roles = explode(',', $role);

        // 3. Usar el método de Spatie para verificar si tiene ALGUNO de esos roles
        // hasAnyRole() revisa automáticamente en la tabla de permisos
        if (! Auth::user()->hasAnyRole($roles)) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}