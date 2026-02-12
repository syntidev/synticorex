<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\TenantContentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class LandingController extends Controller
{
    public function __construct(
        private TenantContentService $contentService
    ) {}

    /**
     * Muestra la landing del tenant inyectado por el middleware.
     * Obtiene el JSON de contenido, elige la plantilla y devuelve la vista.
     */
    public function show(Request $request): View
    {
        $tenant = $request->get('tenant') ?? app('tenant');
        if (!$tenant instanceof Tenant) {
            abort(404, 'Sitio no encontrado o inactivo');
        }

        $content = $this->contentService->get($tenant->slug);
        $template = $this->resolveTemplate($tenant);

        return view($template, [
            'tenant' => $tenant,
            'content' => $content,
        ]);
    }

    /**
     * Vista Blade según tenant->template; por defecto classic.
     */
    private function resolveTemplate(Tenant $tenant): string
    {
        $name = $tenant->template;
        if ($name === null || $name === '') {
            return 'landings.templates.classic.index';
        }
        $view = 'landings.templates.' . $name . '.index';
        return view()->exists($view) ? $view : 'landings.templates.classic.index';
    }
}
