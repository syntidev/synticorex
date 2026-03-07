<?php

declare(strict_types=1);

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {}

    public function show(string $subdomain): JsonResponse
    {
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            return response()->json(['success' => false, 'error' => 'tenant_not_found'], 404);
        }

        return response()->json([
            'success' => true,
            'menu'    => $this->menuService->getMenu($tenant->id),
        ]);
    }
}
