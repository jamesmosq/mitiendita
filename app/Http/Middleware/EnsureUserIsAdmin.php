<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está autenticado, redirigir al login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Si está autenticado pero no es admin, mostrar 403
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
