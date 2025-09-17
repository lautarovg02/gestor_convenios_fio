<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }


        if (!in_array(Auth::user()->role->name, explode(',', $role)) === false) {
            abort(403, 'Acceso denegado');
        }

        return $next($request);
    }
}

