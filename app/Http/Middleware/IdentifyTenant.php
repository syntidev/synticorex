<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class IdentifyTenant
{
    /**
     * Identifica el tenant por host (ecosistema multidominio):
     * - Dominios centrales (tu.menu, menu.vip, etc.): subdominio = slug.
     * - Dominio personalizado: columna tenants.dominio = host.
     * No resuelve tenant en admin_domains (panel/marketing).
     * Inyecta el tenant en el contenedor para que el resto de la app no vuelva a consultar la DB.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        if ($host === '') {
            abort(404, 'Sitio no encontrado o inactivo');
        }

        $adminDomains = config('tenancy.admin_domains', []);
        if (in_array($host, $adminDomains, true)) {
            return $next($request);
        }

        $tenant = $this->resolveTenant($host);
        if ($tenant === null) {
            abort(404, 'Sitio no encontrado o inactivo');
        }

        app()->instance('tenant', $tenant);
        $request->merge(['tenant' => $tenant]);
        view()->share('tenant', $tenant);

        return $next($request);
    }

    /**
     * Resuelve el tenant: por slug (si host pertenece a central_domains) o por dominio personalizado.
     */
    private function resolveTenant(string $host): ?Tenant
    {
        $slug = $this->slugFromCentralDomain($host);
        if ($slug !== null) {
            return Tenant::where('slug', $slug)->where('activo', true)->first();
        }
        return Tenant::where('dominio', $host)->where('activo', true)->first();
    }

    /**
     * Si el host termina en un central_domain, devuelve el subdominio como slug; si no, null.
     * Ej: pepe.tu.menu → 'pepe' | tu.menu → null (sin subdominio).
     */
    private function slugFromCentralDomain(string $host): ?string
    {
        $centralDomains = config('tenancy.central_domains', []);
        $hostLower = strtolower($host);

        foreach ($centralDomains as $root) {
            $rootLower = strtolower($root);
            if ($hostLower === $rootLower) {
                return null;
            }
            $suffix = '.' . $rootLower;
            if (str_ends_with($hostLower, $suffix)) {
                $prefix = substr($hostLower, 0, -strlen($suffix));
                return $prefix !== '' ? $prefix : null;
            }
        }

        return null;
    }
}
