<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ColorPalette;
use App\Models\Tenant;
use App\Models\TenantBranch;
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
                'customization',
                'products' => fn($q) => $q
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'products.galleryImages',
                'services' => fn($q) => $q
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'branches',
            ])
            ->where('id', $tenantId)
            ->whereIn('status', ['active', 'frozen'])
            ->firstOrFail();

            $plan          = $tenant->plan;
            $customization = $tenant->customization;
            $products      = $tenant->products;
            $services      = $tenant->services;
            $branches      = $tenant->branches;

            // Get current dollar rate
            $dollarRate = $this->dollarRateService->getCurrentRate();

            // ── Plan expiry data ─────────────────────────────────────────
            $daysUntilExpiry   = $tenant->daysUntilExpiry();
            $isExpiringSoon    = $tenant->isExpiringSoon();
            $isFrozen          = $tenant->isFrozen();
            $graceRemainingDays = $tenant->graceRemainingDays();

                        $palettes = ColorPalette::where('min_plan_id', '<=', $tenant->plan_id)
                ->orderBy('min_plan_id')
                ->get();

            // THEME SYSTEM - Single Source of Truth: theme_slug
            $currentTheme = $tenant->customization->theme_slug ?? 'light';
            $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? null;
            $hasCustomPalette = !empty($customPalette);
            $activeTheme = $hasCustomPalette ? 'custom' : $currentTheme;

            return view('dashboard.index', compact(
                'tenant',
                'plan',
                'customization',
                'products',
                'services',
                'branches',
                'dollarRate',
                'daysUntilExpiry',
                'isExpiringSoon',
                'isFrozen',
                'graceRemainingDays',
                'palettes',
                'currentTheme',
                'activeTheme',
                'hasCustomPalette'
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
                'contact_maps_url' => 'nullable|string|max:1000',
                'contact_title' => 'nullable|string|max:120',
                'contact_subtitle' => 'nullable|string|max:255',
                'phone_secondary' => 'nullable|string|max:20',
            ]);

            // Update tenant fields (excluding settings-only fields)
            $settingsOnlyKeys = ['contact_maps_url', 'contact_title', 'contact_subtitle', 'phone_secondary'];
            $tenant->update(collect($validated)->except($settingsOnlyKeys)->toArray());

            // Save contact settings in settings JSON (Plan 2+)
            if ($tenant->plan_id >= 2) {
                $settings = $tenant->settings ?? [];
                if ($request->has('contact_maps_url')) {
                    data_set($settings, 'business_info.contact.maps_url', $validated['contact_maps_url'] ?? '');
                }
                if ($request->has('contact_title')) {
                    data_set($settings, 'business_info.contact.title', $validated['contact_title'] ?? '');
                }
                if ($request->has('contact_subtitle')) {
                    data_set($settings, 'business_info.contact.subtitle', $validated['contact_subtitle'] ?? '');
                }
                if ($request->has('phone_secondary')) {
                    data_set($settings, 'contact_info.phone_secondary', $validated['phone_secondary'] ?? '');
                }
                $tenant->settings = $settings;
                $tenant->save();
            }

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

            // Check plan limits (reads from DB)
            $maxProducts = $tenant->plan->products_limit;
            
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

            // Delete gallery images (Plan 3)
            $galleryImages = \App\Models\ProductImage::where('product_id', $productId)->get();
            foreach ($galleryImages as $galleryImage) {
                $galleryPath = storage_path('app/public/tenants/' . $tenantId . '/' . $galleryImage->image_filename);
                if (file_exists($galleryPath)) {
                    unlink($galleryPath);
                }
            }
            // DB records cascade-deleted via FK

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
            $maxServices = $tenant->plan->services_limit;
            
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
                'icon_name' => 'nullable|string|max:50',
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
                'icon_name' => 'nullable|string|max:50',
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

    /**
     * Update tenant FlyonUI theme.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updateTheme(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

                        // SIEMPRE limpiar custom palette cuando se selecciona tema FlyonUI
            $settings = $tenant->settings ?? [];
            if (isset($settings['engine_settings']['visual']['custom_palette'])) {
                unset($settings['engine_settings']['visual']['custom_palette']);
                $tenant->settings = $settings;
                $tenant->save();
            }

            // 17 temas oficiales FlyonUI (única fuente de verdad)
            $validThemes = [
                'light', 'dark', 'black', 'claude', 'corporate', 'ghibli', 'gourmet',
                'luxury', 'mintlify', 'pastel', 'perplexity', 'shadcn', 'slack',
                'soft', 'spotify', 'valorant', 'vscode'
            ];

            // Validate input
            $validated = $request->validate([
                'theme_slug' => 'required|string|in:' . implode(',', $validThemes)
            ]);

            // Get or create customization record
            $customization = $tenant->customization;
            if (!$customization) {
                $customization = new \App\Models\TenantCustomization();
                $customization->tenant_id = $tenant->id;
            }

            // Update theme
            $customization->theme_slug = $validated['theme_slug'];
            $customization->save();

            return response()->json([
                'success' => true,
                'message' => 'Tema actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tema: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update tenant color palette (LEGACY - use updateTheme instead).
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updatePalette(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Lista de 17 temas válidos de FlyonUI
            $validThemes = [
                'light', 'dark', 'black', 'claude', 'corporate', 'ghibli',
                'gourmet', 'luxury', 'mintlify', 'pastel', 'perplexity',
                'shadcn', 'slack', 'soft', 'spotify', 'valorant', 'vscode'
            ];

            // Validate input
            $validated = $request->validate([
                'theme' => 'required|string|in:' . implode(',', $validThemes)
            ]);

            // Update settings JSON
            $settings = $tenant->settings ?? [];
            if (!isset($settings['engine_settings'])) {
                $settings['engine_settings'] = [];
            }
            if (!isset($settings['engine_settings']['visual'])) {
                $settings['engine_settings']['visual'] = [];
            }
            if (!isset($settings['engine_settings']['visual']['theme'])) {
                $settings['engine_settings']['visual']['theme'] = [];
            }
            
            $settings['engine_settings']['visual']['theme']['flyonui_theme'] = $validated['theme'];
            $tenant->settings = $settings;
            $tenant->save();

            // Also sync to customization->theme_slug for consistency
            $customization = $tenant->customization;
            if (!$customization) {
                $customization = new \App\Models\TenantCustomization();
                $customization->tenant_id = $tenant->id;
            }
            $customization->theme_slug = $validated['theme'];
            $customization->save();

            // Clear compiled views so landing reflects the new theme immediately
            \Illuminate\Support\Facades\Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'Tema actualizado correctamente',
                'theme'   => $validated['theme'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar paleta: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update currency configuration settings.
     */
    public function updateCurrencyConfig(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $settings = $tenant->settings ?? [];
            
            // Ensure currency structure exists
            if (!isset($settings['engine_settings'])) {
                $settings['engine_settings'] = [];
            }
            if (!isset($settings['engine_settings']['currency'])) {
                $settings['engine_settings']['currency'] = [];
            }
            if (!isset($settings['engine_settings']['currency']['display'])) {
                $settings['engine_settings']['currency']['display'] = [];
            }

            // Get request values
            $displayMode = $request->input('display_mode', 'reference_only');
            $symbol = $request->input('symbol', 'REF');

            // Mapear display_mode a flags booleanos
            $showReference = in_array($displayMode, ['reference_only', 'both_toggle']);
            $showBolivares = in_array($displayMode, ['bolivares_only', 'both_toggle']);
            $hidePrice     = $displayMode === 'hidden';
            $hasToggle     = $displayMode === 'both_toggle';

            $settings['engine_settings']['currency']['display']['show_reference'] = $showReference;
            $settings['engine_settings']['currency']['display']['show_bolivares'] = $showBolivares;
            $settings['engine_settings']['currency']['display']['hide_price']     = $hidePrice;
            $settings['engine_settings']['currency']['display']['has_toggle']     = $hasToggle;
            $settings['engine_settings']['currency']['display']['symbols']['reference'] = $symbol;
            $settings['engine_settings']['currency']['display']['saved_display_mode'] = $displayMode;

            $tenant->settings = $settings;
            $tenant->save();

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar configuración: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update tenant PIN.
     */
    public function updatePin(Request $request, int $tenantId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'current_pin' => 'required|string|size:4|regex:/^[0-9]{4}$/',
                'new_pin' => 'required|string|size:4|regex:/^[0-9]{4}$/',
                'new_pin_confirmation' => 'required|string|same:new_pin'
            ]);

            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Verify current PIN
            if (!\Illuminate\Support\Facades\Hash::check($validated['current_pin'], $tenant->pin_hash)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El PIN actual es incorrecto'
                ], 422);
            }

            // Update PIN
            $tenant->pin_hash = \Illuminate\Support\Facades\Hash::make($validated['new_pin']);
            $tenant->save();

            return response()->json([
                'success' => true,
                'message' => 'PIN actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar PIN: ' . $e->getMessage()
            ], 422);
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // BRANCHES (Plan 3 / VISIÓN)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Toggle branches section visibility (stored in tenant settings).
     */
    public function toggleBranches(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('plan')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            if ((int) $tenant->plan_id !== 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'La sección de sucursales solo está disponible en el Plan Visión'
                ], 403);
            }

            $validated = $request->validate([
                'enabled' => 'required|boolean',
            ]);

            $settings = $tenant->settings ?? [];
            data_set($settings, 'engine_settings.branches.enabled', (bool) $validated['enabled']);
            $tenant->settings = $settings;
            $tenant->save();

            return response()->json([
                'success' => true,
                'enabled' => (bool) $validated['enabled'],
                'message' => $validated['enabled'] ? 'Sección de sucursales activada' : 'Sección de sucursales desactivada',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Create or update a branch (max 3 per tenant, Plan 3 only).
     */
    public function saveBranch(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('plan')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            if ((int) $tenant->plan_id !== 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo disponible en Plan Visión'
                ], 403);
            }

            $validated = $request->validate([
                'id' => 'nullable|integer',
                'name' => 'required|string|max:150',
                'address' => 'required|string|max:500',
                'is_active' => 'boolean',
            ]);

            if (!empty($validated['id'])) {
                // Update existing
                $branch = TenantBranch::where('id', $validated['id'])
                    ->where('tenant_id', $tenantId)
                    ->firstOrFail();

                $branch->update([
                    'name' => $validated['name'],
                    'address' => $validated['address'],
                    'is_active' => $validated['is_active'] ?? true,
                ]);
            } else {
                // Create new — enforce max 3
                $currentCount = TenantBranch::where('tenant_id', $tenantId)->count();
                if ($currentCount >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Máximo 3 sucursales permitidas'
                    ], 422);
                }

                $branch = TenantBranch::create([
                    'tenant_id' => $tenantId,
                    'name' => $validated['name'],
                    'address' => $validated['address'],
                    'is_active' => $validated['is_active'] ?? true,
                ]);
            }

            return response()->json([
                'success' => true,
                'branch' => $branch->fresh(),
                'message' => !empty($validated['id']) ? 'Sucursal actualizada' : 'Sucursal creada',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete a branch.
     */
    public function deleteBranch(int $tenantId, int $branchId): JsonResponse
    {
        try {
            $branch = TenantBranch::where('id', $branchId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            $branch->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sucursal eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // PAYMENT METHODS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Update tenant payment methods.
     * Plan 1: fixed (Pago Móvil + Biopago), not editable.
     * Plan 2: global selection only.
     * Plan 3: global + per-branch assignment.
     */
    public function updatePaymentMethods(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with(['plan', 'customization', 'branches'])
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $plan = $tenant->plan;

            if ((int) $plan->id === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'El Plan OPORTUNIDAD tiene medios de pago fijos',
                ], 422);
            }

            $allowedMethods    = ['pagoMovil', 'cash', 'puntoventa', 'biopago', 'cashea', 'krece', 'wepa', 'lysto', 'chollo', 'zelle', 'zinli', 'paypal'];
            $allowedCurrencies = ['usd', 'eur'];

            $validated = $request->validate([
                'global'     => 'nullable|array',
                'global.*'   => 'string|in:' . implode(',', $allowedMethods),
                'currency'   => 'nullable|array',
                'currency.*' => 'string|in:' . implode(',', $allowedCurrencies),
                'branches'   => 'nullable|array',
            ]);

            $data = [
                'global'   => array_values(
                    array_intersect($validated['global'] ?? [], $allowedMethods)
                ),
                'currency' => array_values(
                    array_intersect($validated['currency'] ?? [], $allowedCurrencies)
                ),
            ];

            // Plan 3: accept per-branch assignment
            if ((int) $plan->id === 3) {
                $branchIds  = $tenant->branches->pluck('id')->toArray();
                $branchData = [];

                foreach ($validated['branches'] ?? [] as $branchId => $methods) {
                    if (in_array((int) $branchId, $branchIds, true)) {
                        $branchData[(string) $branchId] = array_values(
                            array_intersect((array) $methods, $allowedMethods)
                        );
                    }
                }

                $data['branches'] = $branchData;
            }

            $customization = $tenant->customization
                ?? \App\Models\TenantCustomization::firstOrCreate(['tenant_id' => $tenantId]);

            $customization->payment_methods = $data;
            $customization->save();

            return response()->json([
                'success'         => true,
                'message'         => 'Medios de pago actualizados correctamente',
                'payment_methods' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    // SOCIAL NETWORKS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Update tenant social networks.
     * Plan 1: max 1 network. Plan 2+3: all 6.
     */
    public function updateSocialNetworks(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with(['plan', 'customization'])
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $plan = $tenant->plan;

            // Allowed networks by plan
            $allNetworks = ['instagram', 'facebook', 'tiktok', 'linkedin', 'youtube', 'x'];
            $plan1Networks = ['instagram', 'facebook', 'tiktok', 'linkedin'];

            $allowed = (int) $plan->id === 1 ? $plan1Networks : $allNetworks;

            // Build validation rules
            $rules = [];
            foreach ($allowed as $network) {
                $rules[$network] = 'nullable|string|max:255';
            }

            $validated = $request->validate($rules);

            // Filter only non-empty values and allowed networks
            $networks = [];
            foreach ($allowed as $network) {
                $value = trim($validated[$network] ?? '');
                if ($value !== '') {
                    $networks[$network] = $value;
                }
            }

            // Plan 1: enforce max 1 network
            if ((int) $plan->id === 1 && count($networks) > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'El Plan OPORTUNIDAD solo permite configurar 1 red social',
                ], 422);
            }

            // Save to customization
            $customization = $tenant->customization
                ?? \App\Models\TenantCustomization::firstOrCreate(['tenant_id' => $tenantId]);

            $customization->social_networks = $networks;
            $customization->save();

            return response()->json([
                'success' => true,
                'message' => 'Redes sociales actualizadas correctamente',
                'networks' => $networks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function saveSectionOrder(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('customization')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $validated = $request->validate([
                'sections_order'           => 'required|array',
                'sections_order.*.name'    => 'required|string|max:64',
                'sections_order.*.visible' => 'required|boolean',
                'sections_order.*.order'   => 'required|integer|min:0',
            ]);

            $customization = $tenant->customization
                ?? \App\Models\TenantCustomization::firstOrCreate(['tenant_id' => $tenantId]);

            $visualEffects = $customization->visual_effects ?? [];
            $visualEffects['sections_order'] = $validated['sections_order'];
            $customization->visual_effects = $visualEffects;
            $customization->save();

            // Sincronizar sections_config con el nuevo sections_order
            $customization->syncSectionsConfig();

            return response()->json([
                'success' => true,
                'message' => 'Orden de secciones guardado correctamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 422);
        }
    }

        /**
     * Save custom color palette for Plan 3 tenants.
     */
    public function saveCustomPalette(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('customization')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            if ((int) $tenant->plan_id !== 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'La paleta personalizada solo está disponible en el Plan Visión'
                ], 403);
            }

            // Guardar custom palette en settings
            $settings = $tenant->settings ?? [];
            $settings['engine_settings']['visual']['custom_palette'] = [
                'primary'   => $request->primary,
                'secondary' => $request->secondary,
                'accent'    => $request->accent,
                'base'      => $request->base,
            ];
            $tenant->settings = $settings;
            $tenant->save();

            // Marcar theme_slug como NULL (custom mode)
            $customization = $tenant->customization;
            if (!$customization) {
                $customization = new \App\Models\TenantCustomization();
                $customization->tenant_id = $tenant->id;
            }
            $customization->theme_slug = null;
            $customization->save();

            return response()->json([
                'success' => true,
                'message' => 'Paleta personalizada guardada'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }
}
