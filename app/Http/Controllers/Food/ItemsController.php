<?php

declare(strict_types=1);

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {}

    public function index(int $tenantId, string $category): JsonResponse
    {
        $tenant = Tenant::findOrFail($tenantId);
        $cat = $this->menuService->getCategory($tenant->id, $category);

        if (!$cat) {
            return response()->json(['success' => false, 'error' => 'category_not_found'], 404);
        }

        return response()->json([
            'success' => true,
            'items'   => $cat['items'] ?? [],
        ]);
    }

    public function store(Request $request, int $tenantId, string $category): JsonResponse
    {
        $tenant = Tenant::with('plan')->findOrFail($tenantId);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:200'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        $planId = (int) ($tenant->plan_id ?? 1);
        $limits = MenuService::limits($planId);
        $currentItems = $this->menuService->countItems($tenant->id);

        if ($currentItems >= $limits['items']) {
            return response()->json([
                'success' => false,
                'error'   => 'item_limit_reached',
                'limit'   => $limits['items'],
            ], 422);
        }

        $item = $this->menuService->createItem($tenant->id, $category, $validated);

        if (!$item) {
            return response()->json(['success' => false, 'error' => 'category_not_found'], 404);
        }

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function update(Request $request, int $tenantId, string $category, string $item): JsonResponse
    {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:200'],
            'precio' => ['sometimes', 'required', 'numeric', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $updated = $this->menuService->updateItem($tenant->id, $category, $item, $validated);

        if (!$updated) {
            return response()->json(['success' => false, 'error' => 'item_not_found'], 404);
        }

        return response()->json(['success' => true, 'item' => $updated]);
    }

    public function destroy(int $tenantId, string $category, string $item): JsonResponse
    {
        $tenant = Tenant::findOrFail($tenantId);

        if (!$this->menuService->deleteItem($tenant->id, $category, $item)) {
            return response()->json(['success' => false, 'error' => 'item_not_found'], 404);
        }

        return response()->json(['success' => true]);
    }
}
