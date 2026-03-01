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
     *
     * MODO SUBDOMINIO (producción): pepe.tu.menu → subdomain='pepe'
     * MODO PATH (local):            synticorex.test/pepe → path segment 1 = 'pepe'
     * CUSTOM DOMAIN:                host = tenants.custom_domain
     *
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

        $tenant = $this->resolveTenant($host, $request);
        if ($tenant === null) {
            abort(404, 'Sitio no encontrado o inactivo');
        }

        app()->instance('tenant', $tenant);
        $request->merge(['tenant' => $tenant]);
        view()->share('tenant', $tenant);

        return $next($request);
    }

    /**
     * Resuelve el tenant por tres vías (en orden):
     *
     * 1. SUBDOMINIO: host tiene prefijo sobre un central_domain → pepe.tu.menu
     * 2. PATH-MODE:  host ES un central_domain raíz sin subdominio → synticorex.test/pepe
     * 3. CUSTOM DOMAIN: host coincide con tenants.custom_domain
     */
    private function resolveTenant(string $host, Request $request): ?Tenant
    {
        // 1. Modo subdominio: pepe.tu.menu → subdomain = 'pepe'
        $subdomain = $this->slugFromCentralDomain($host);
        if ($subdomain !== null) {
            return Tenant::where('subdomain', $subdomain)->where('status', 'active')->first();
        }

        // 2. Path-mode: host ES el dominio raíz → leer tenant del primer segmento de la URL
        //    Ej: synticorex.test/pepe → segment(1) = 'pepe'
        $centralDomains = config('tenancy.central_domains', []);
        $hostLower = strtolower($host);
        if (in_array($hostLower, array_map('strtolower', $centralDomains), true)) {
            $pathSlug = $request->segment(1);
            if ($pathSlug !== null && $pathSlug !== '') {
                return Tenant::where('subdomain', $pathSlug)->where('status', 'active')->first();
            }
            return null;
        }

        // 3. Custom domain: host = tenants.custom_domain
        return Tenant::where('custom_domain', $host)->where('status', 'active')->first();
    }

    /**
     * Si el host termina en un central_domain, devuelve el subdominio; si no, null.
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
