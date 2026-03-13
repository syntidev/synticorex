<?php

declare(strict_types=1);

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\ImageUploadService;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function __construct(
        private readonly MenuService $menuService,
        private readonly ImageUploadService $imageService,
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
            'nombre'      => ['required', 'string', 'max:200'],
            'precio'      => ['required', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string', 'max:200'],
            'badge'       => ['nullable', 'string', 'max:50'],
            'is_featured' => ['nullable', 'boolean'],
            'imagen'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'options'     => ['nullable', 'json'],
        ]);

        // Validate options if provided
        $options = [];
        if (!empty($validated['options'])) {
            $optionsData = json_decode($validated['options'], true) ?? [];
            if (is_array($optionsData) && count($optionsData) <= 8) {
                foreach ($optionsData as $opt) {
                    if (!is_array($opt)) continue;
                    $label = trim($opt['label'] ?? '');
                    $priceAdd = (float) ($opt['price_add'] ?? 0);
                    if (!empty($label) && strlen($label) <= 80 && $priceAdd >= 0 && $priceAdd <= 50) {
                        $options[] = [
                            'id' => $opt['id'] ?? uniqid('opt_'),
                            'label' => $label,
                            'price_add' => $priceAdd,
                        ];
                    }
                }
            }
        }

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

        $itemData = [
            'nombre'      => $validated['nombre'],
            'precio'      => $validated['precio'],
            'descripcion' => $validated['descripcion'] ?? null,
            'badge'       => $validated['badge'] ?? null,
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'options'     => !empty($options) ? $options : null,
        ];

        $item = $this->menuService->createItem($tenant->id, $category, $itemData);

        if (!$item) {
            return response()->json(['success' => false, 'error' => 'category_not_found'], 404);
        }

        $warning = null;
        if ($request->hasFile('imagen')) {
            try {
                $imagePath = $this->processItemImage($request->file('imagen'), $tenant->id, $item['id']);
                $this->menuService->updateItem($tenant->id, $category, $item['id'], ['image_path' => $imagePath]);
                $item['image_path'] = $imagePath;
            } catch (\Throwable $e) {
                report($e);
                $warning = 'El plato se guardó pero la imagen no pudo procesarse. Intenta subirla de nuevo.';
            }
        }

        $response = ['success' => true, 'item' => $item];
        if ($warning) {
            $response['warning'] = $warning;
        }
        if (!empty($item['image_path'])) {
            $response['image_url'] = asset('storage/tenants/' . $tenant->id . '/' . $item['image_path']);
        }
        return response()->json($response, 201);
    }

    public function update(Request $request, int $tenantId, string $category, string $item): JsonResponse
    {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([
            'nombre'       => ['sometimes', 'required', 'string', 'max:200'],
            'precio'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'descripcion'  => ['nullable', 'string', 'max:200'],
            'badge'        => ['nullable', 'string', 'max:50'],
            'is_featured'  => ['nullable', 'boolean'],
            'activo'       => ['sometimes', 'boolean'],
            'imagen'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'options'      => ['nullable', 'json'],
        ]);

        // Validate and process options if provided
        if (array_key_exists('options', $validated)) {
            $options = [];
            if (!empty($validated['options'])) {
                $optionsData = json_decode($validated['options'], true) ?? [];
                if (is_array($optionsData) && count($optionsData) <= 8) {
                    foreach ($optionsData as $opt) {
                        if (!is_array($opt)) continue;
                        $label = trim($opt['label'] ?? '');
                        $priceAdd = (float) ($opt['price_add'] ?? 0);
                        if (!empty($label) && strlen($label) <= 80 && $priceAdd >= 0 && $priceAdd <= 50) {
                            $options[] = [
                                'id' => $opt['id'] ?? uniqid('opt_'),
                                'label' => $label,
                                'price_add' => $priceAdd,
                            ];
                        }
                    }
                }
            }
            $validated['options'] = !empty($options) ? $options : null;
        }

        if ($request->boolean('remove_image')) {
            $this->removeItemImage($tenant->id, $item);
            $validated['image_path'] = null;
        }

        $warning = null;
        if ($request->hasFile('imagen')) {
            try {
                $imagePath = $this->processItemImage($request->file('imagen'), $tenant->id, $item);
                $validated['image_path'] = $imagePath;
            } catch (\Throwable $e) {
                report($e);
                $warning = 'El plato se actualizó pero la imagen no pudo procesarse. Intenta subirla de nuevo.';
            }
        }

        unset($validated['imagen'], $validated['remove_image']);

        $updated = $this->menuService->updateItem($tenant->id, $category, $item, $validated);

        if (!$updated) {
            return response()->json(['success' => false, 'error' => 'item_not_found'], 404);
        }

        $response = ['success' => true, 'item' => $updated];
        if ($warning) {
            $response['warning'] = $warning;
        }
        if (!empty($updated['image_path'])) {
            $response['image_url'] = asset('storage/tenants/' . $tenant->id . '/' . $updated['image_path']);
        }
        return response()->json($response);
    }

    public function destroy(int $tenantId, string $category, string $item): JsonResponse
    {
        $tenant = Tenant::findOrFail($tenantId);

        if (!$this->menuService->deleteItem($tenant->id, $category, $item)) {
            return response()->json(['success' => false, 'error' => 'item_not_found'], 404);
        }

        return response()->json(['success' => true]);
    }

    private function processItemImage(\Illuminate\Http\UploadedFile $file, int $tenantId, string $itemId): string
    {
        $subDir = storage_path("app/public/tenants/{$tenantId}/menu/items");
        if (!is_dir($subDir)) {
            mkdir($subDir, 0755, true);
        }

        $customFilename = "menu/items/{$itemId}.webp";
        $this->imageService->processWithCustomFilename($file, $tenantId, $customFilename, 800);

        return $customFilename;
    }

    private function removeItemImage(int $tenantId, string $itemId): void
    {
        $path = storage_path("app/public/tenants/{$tenantId}/menu/items/{$itemId}.webp");
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
