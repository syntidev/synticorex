<?php

declare(strict_types=1);

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {}

    public function index(int $tenantId): JsonResponse
    {
        $tenant = Tenant::with('plan')->findOrFail($tenantId);

        return response()->json([
            'success'    => true,
            'categories' => $this->menuService->getCategories($tenant->id),
        ]);
    }

    public function store(Request $request, int $tenantId): JsonResponse
    {
        $tenant = Tenant::with('plan')->findOrFail($tenantId);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'foto'   => ['nullable', 'string', 'max:255'],
        ]);

        $planId = (int) ($tenant->plan_id ?? 1);
        $limits = MenuService::limits($planId);
        $currentPhotos = collect($this->menuService->getCategories($tenant->id))
            ->filter(fn(array $c) => !empty($c['foto']))
            ->count();

        if (!empty($validated['foto']) && $currentPhotos >= $limits['photos']) {
            return response()->json([
                'success' => false,
                'error'   => 'photo_limit_reached',
                'limit'   => $limits['photos'],
            ], 422);
        }

        $category = $this->menuService->createCategory($tenant->id, $validated);

        return response()->json(['success' => true, 'category' => $category], 201);
    }

    public function update(Request $request, int $tenantId, string $category): JsonResponse
    {
        $tenant = Tenant::with('plan')->findOrFail($tenantId);

        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:120'],
            'foto'   => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $updated = $this->menuService->updateCategory($tenant->id, $category, $validated);

        if (!$updated) {
            return response()->json(['success' => false, 'error' => 'category_not_found'], 404);
        }

        return response()->json(['success' => true, 'category' => $updated]);
    }

    public function destroy(int $tenantId, string $category): JsonResponse
    {
        $tenant = Tenant::findOrFail($tenantId);

        if (!$this->menuService->deleteCategory($tenant->id, $category)) {
            return response()->json(['success' => false, 'error' => 'category_not_found'], 404);
        }

        return response()->json(['success' => true]);
    }
}
