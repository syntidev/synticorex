<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\DollarRateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * @param DollarRateService $dollarRateService
     */
    public function __construct(
        private readonly DollarRateService $dollarRateService
    ) {}

    /**
     * Display tenant dashboard.
     *
     * @param int $tenantId
     * @return View|Response
     */
    public function index(int $tenantId): View|Response
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::with([
                'plan',
                'colorPalette',
                'customization',
                'products' => fn($q) => $q
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'services' => fn($q) => $q
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
            ])
            ->where('id', $tenantId)
            ->where('status', 'active')
            ->firstOrFail();

            $plan = $tenant->plan;
            $customization = $tenant->customization;
            $products = $tenant->products;
            $services = $tenant->services;

            // Get current dollar rate
            $dollarRate = $this->dollarRateService->getCurrentRate() ?? 36.50;

            return view('dashboard.index', compact(
                'tenant',
                'plan',
                'customization',
                'products',
                'services',
                'dollarRate'
            ));
        } catch (\Exception $e) {
            return response()->view('errors.404', [], 404);
        }
    }

    /**
     * Update tenant information.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updateInfo(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Validate input
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'slogan' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'whatsapp_sales' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:500',
                'is_open' => 'nullable|boolean',
            ]);

            // Update tenant
            $tenant->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Información actualizada'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar información: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Create new product.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function createProduct(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('plan', 'products')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Check plan limits
            $productLimits = [1 => 6, 2 => 18, 3 => 40];
            $maxProducts = $productLimits[$tenant->plan->id] ?? 6;
            
            if ($tenant->products->count() >= $maxProducts) {
                return response()->json([
                    'success' => false,
                    'message' => 'Has alcanzado el límite de productos de tu plan'
                ], 422);
            }

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'price_usd' => 'required|numeric|min:0',
                'badge' => 'nullable|in:hot,new,promo',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
            ]);

            // Get next position
            $maxPosition = $tenant->products()->max('position') ?? 0;
            $validated['position'] = $maxPosition + 1;
            $validated['tenant_id'] = $tenantId;

            // Create product
            $product = \App\Models\Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Producto creado',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear producto: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update existing product.
     *
     * @param Request $request
     * @param int $tenantId
     * @param int $productId
     * @return JsonResponse
     */
    public function updateProduct(Request $request, int $tenantId, int $productId): JsonResponse
    {
        try {
            // Find tenant and product
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $product = \App\Models\Product::where('id', $productId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'price_usd' => 'required|numeric|min:0',
                'badge' => 'nullable|in:hot,new,promo',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
            ]);

            // Update product
            $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar producto: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete product.
     *
     * @param int $tenantId
     * @param int $productId
     * @return JsonResponse
     */
    public function deleteProduct(int $tenantId, int $productId): JsonResponse
    {
        try {
            // Find tenant and product
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $product = \App\Models\Product::where('id', $productId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Delete image if exists
            if ($product->image_filename) {
                $imagePath = storage_path('app/public/tenants/' . $tenantId . '/' . $product->image_filename);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete product
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Create new service.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function createService(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('plan', 'services')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Check plan limits
            $serviceLimits = [1 => 3, 2 => 6, 3 => 15];
            $maxServices = $serviceLimits[$tenant->plan->id] ?? 3;
            
            if ($tenant->services->count() >= $maxServices) {
                return response()->json([
                    'success' => false,
                    'message' => 'Has alcanzado el límite de servicios de tu plan'
                ], 422);
            }

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable|boolean',
            ]);

            // Get next position
            $maxPosition = $tenant->services()->max('position') ?? 0;
            $validated['position'] = $maxPosition + 1;
            $validated['tenant_id'] = $tenantId;

            // Create service
            $service = \App\Models\Service::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Servicio creado',
                'service' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear servicio: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update existing service.
     *
     * @param Request $request
     * @param int $tenantId
     * @param int $serviceId
     * @return JsonResponse
     */
    public function updateService(Request $request, int $tenantId, int $serviceId): JsonResponse
    {
        try {
            // Find tenant and service
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $service = \App\Models\Service::where('id', $serviceId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable|boolean',
            ]);

            // Update service
            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Servicio actualizado',
                'service' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar servicio: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete service.
     *
     * @param int $tenantId
     * @param int $serviceId
     * @return JsonResponse
     */
    public function deleteService(int $tenantId, int $serviceId): JsonResponse
    {
        try {
            // Find tenant and service
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $service = \App\Models\Service::where('id', $serviceId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Delete image if exists
            if ($service->image_filename) {
                $imagePath = storage_path('app/public/tenants/' . $tenantId . '/' . $service->image_filename);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete service
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Servicio eliminado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar servicio: ' . $e->getMessage()
            ], 422);
        }
    }
}
