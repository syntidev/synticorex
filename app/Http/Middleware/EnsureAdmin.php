<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->guest(route('login'))
                ->with('warning', 'Tu sesión expiró. Inicia sesión para continuar.');
        }

        if (!$request->user()->isAdmin()) {
            return redirect()->route('tenants.index')
                ->with('error', 'Acceso restringido a administradores.');
        }

        return $next($request);
    }
}
