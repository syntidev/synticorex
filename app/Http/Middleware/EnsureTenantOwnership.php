<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureTenantOwnership
{
    /**
     * Ensure the authenticated user owns the tenant referenced in route params.
     *
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next, string $tenantParameter = 'tenantId'): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'))
                ->with('warning', 'Tu sesión expiró. Inicia sesión para continuar.');
        }

        $tenantIdRaw = $request->route($tenantParameter);
        if ($tenantIdRaw === null || $tenantIdRaw === '') {
            abort(404, 'Tenant no encontrado.');
        }

        $tenantId = (int) $tenantIdRaw;
        if ($tenantId <= 0) {
            abort(404, 'Tenant no encontrado.');
        }

        $isOwner = Tenant::where('id', $tenantId)
            ->where('user_id', $user->id)
            ->exists();

        if ($isOwner) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para este tenant.',
            ], 403);
        }

        return redirect()->route('tenants.index')
            ->with('error', 'No tienes acceso a ese negocio.');
    }
}
