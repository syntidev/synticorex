<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ColorPalette;
use App\Models\Tenant;
use App\Models\TenantBranch;
use App\Services\DollarRateService;
use App\Services\MenuService;
use App\Services\PrelineThemeService;
use App\Services\QRService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * @param DollarRateService $dollarRateService
     * @param QRService $qrService
     */
    public function __construct(
        private readonly DollarRateService $dollarRateService,
        private readonly QRService $qrService
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
            $customization = $tenant->customization
                ?? \App\Models\TenantCustomization::create([
                    'tenant_id'   => $tenant->id,
                    'hero_layout' => 'gradient',
                ]);
            $products      = $tenant->products;
            $services      = $tenant->services;
            $branches      = $tenant->branches;

            // Get current dollar and euro rates
            $dollarRate = $this->dollarRateService->getCurrentRate();
            $euroRate   = $this->dollarRateService->getCurrentEuroRate();

            // ── Plan expiry data ─────────────────────────────────────────
            $daysUntilExpiry   = $tenant->daysUntilExpiry();
            $isExpiringSoon    = $tenant->isExpiringSoon();
            $isFrozen          = $tenant->isFrozen();
            $graceRemainingDays = $tenant->graceRemainingDays();

                        $palettes = ColorPalette::where('min_plan_id', '<=', $tenant->plan_id)
                ->orderBy('min_plan_id')
                ->get();

            // ── Blueprint system ─────────────────────────────────────────
            $blueprint  = $tenant->getBlueprintSlug();
            $maxItems   = $tenant->getMaxItems();
            $itemLabel  = $tenant->getItemLabel();
            $itemSingular = $tenant->getItemSingular();

            // THEME SYSTEM - Single Source of Truth: theme_slug
            $currentTheme = $customization->theme_slug ?? 'default';
            $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? null;
            $hasCustomPalette = !empty($customPalette);
            $activeTheme = $hasCustomPalette ? 'custom' : $currentTheme;

            // Generate QR code for traffic tracking (private - only visible in dashboard)
            $trackingQR = $this->qrService->generateQR($tenant->id, 300);
            $trackingShortlink = $this->qrService->getTrackingShortlink($tenant->id);

            // ── Testimonials data ─────────────────────────────────────────
            $savedTestimonials = data_get($tenant->settings, 'business_info.testimonials', []);

            // ── Social Networks & FAQ ─────────────────────────────────────
            $plan1NetworksList = ['instagram', 'facebook', 'tiktok', 'linkedin', 'youtube', 'twitter'];
            $plan1Networks     = $plan1NetworksList;
            $savedFaq          = data_get($tenant->settings, 'business_info.faq', []);

            // ── Payment Methods & Currency Metadata ───────────────────────
            $allPayMeta = [
                // ── Nacionales ──────────────────────
                'pagoMovil'  => ['icon' => 'tabler--device-mobile',   'label' => 'Pago Móvil',    'group' => 'Nacional'],
                'cash'       => ['icon' => 'tabler--cash',            'label' => 'Efectivo',       'group' => 'Nacional'],
                'puntoventa' => ['icon' => 'tabler--credit-card',     'label' => 'Punto de Venta', 'group' => 'Nacional'],
                'biopago'    => ['icon' => 'tabler--fingerprint',     'label' => 'Biopago',        'group' => 'Nacional'],
                'cashea'     => ['icon' => 'tabler--wallet',          'label' => 'Cashea',         'group' => 'Nacional'],
                'krece'      => ['icon' => 'tabler--trending-up',     'label' => 'Krece',          'group' => 'Nacional'],
                'wepa'       => ['icon' => 'tabler--shopping-cart',   'label' => 'Wepa',           'group' => 'Nacional'],
                'lysto'      => ['icon' => 'tabler--calendar-dollar', 'label' => 'Lysto',          'group' => 'Nacional'],
                'chollo'     => ['icon' => 'tabler--discount-2',      'label' => 'Chollo',         'group' => 'Nacional'],
                'wally'      => ['icon' => 'tabler--send-2',          'label' => 'Wally',          'group' => 'Nacional'],
                'kontigo'    => ['icon' => 'tabler--file-invoice',    'label' => 'Kontigo',        'group' => 'Nacional'],
                // ── Internacionales / Divisas ────────
                'zelle'      => ['icon' => 'tabler--bolt',            'label' => 'Zelle',          'group' => 'Divisa'],
                'paypal'     => ['icon' => 'tabler--brand-paypal',    'label' => 'PayPal',         'group' => 'Divisa'],
                'zinli'      => ['icon' => 'tabler--moneybag',          'label' => 'Zinli',          'group' => 'Divisa'],
                'airtm'      => ['icon' => 'tabler--exchange',        'label' => 'AirTM',          'group' => 'Divisa'],
                'reserve'    => ['icon' => 'tabler--shield-dollar',   'label' => 'Reserve (RSV)',  'group' => 'Divisa'],
                'binancepay' => ['icon' => 'tabler--currency-bitcoin','label' => 'Binance Pay',    'group' => 'Divisa'],
                'usdt'       => ['icon' => 'tabler--coin',            'label' => 'USDT',           'group' => 'Divisa'],
            ];

            $allCurrencyMeta = [
                'usd' => ['icon' => 'tabler--currency-dollar', 'label' => 'Dólares (USD)'],
                'eur' => ['icon' => 'tabler--currency-euro',   'label' => 'Euros (€)'],
            ];

            // ── Branches (Plan 3) ─────────────────────────────────────────
            $activeBranchList = $tenant->branches()->where('is_active', true)->get();

            // ── Theme Slug ────────────────────────────────────────────────
            $themeSlug = $tenant->customization?->theme_slug ?? 'default';

            // ── Orders (Mini Order Engine — cat-anual) ────────────────────
            $isPlanAnual = $plan && $plan->slug === 'cat-anual';
            $orders = [];

            // ── Menu (SYNTIfood) ──────────────────────────────────────────
            $menu = $blueprint === 'food'
                ? (new MenuService())->getCategories($tenant->id)
                : [];

            return view('dashboard.index', compact(
                'tenant',
                'plan',
                'customization',
                'products',
                'services',
                'branches',
                'dollarRate',
                'euroRate',
                'daysUntilExpiry',
                'isExpiringSoon',
                'isFrozen',
                'graceRemainingDays',
                'palettes',
                'currentTheme',
                'activeTheme',
                'hasCustomPalette',
                'trackingQR',
                'trackingShortlink',
                'savedTestimonials',
                'plan1NetworksList',
                'plan1Networks',
                'savedFaq',
                'allPayMeta',
                'allCurrencyMeta',
                'activeBranchList',
                'blueprint',
                'maxItems',
                'itemLabel',
                'itemSingular',
                'themeSlug',
                'orders',
                'isPlanAnual',
                'menu'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard index error for tenant ' . $tenantId, [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->view('errors.500', ['exception' => $e], 500);
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
                'business_name' => 'sometimes|required|string|max:255',
                'slogan' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'whatsapp_sales' => 'nullable|string|max:20',
                'whatsapp_support' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:1000',
                'meta_title' => 'nullable|string|max:120',
                'meta_description' => 'nullable|string|max:255',
                'meta_keywords' => 'nullable|string|max:255',
                'is_open' => 'nullable|boolean',
                'contact_maps_url' => 'nullable|string|max:1000',
                'contact_title' => 'nullable|string|max:120',
                'contact_subtitle' => 'nullable|string|max:255',
                'show_hours_indicator' => 'nullable|boolean',
                'closed_message' => 'nullable|string|max:255',
                'content_blocks'                        => 'nullable|array',
                'content_blocks.hero'               => 'nullable|array',
                'content_blocks.hero.title'         => 'nullable|string|max:100',
                'content_blocks.hero.subtitle'      => 'nullable|string|max:200',
                'content_blocks.products'           => 'nullable|array',
                'content_blocks.products.title'     => 'nullable|string|max:80',
                'content_blocks.products.subtitle'  => 'nullable|string|max:200',
                'content_blocks.services'           => 'nullable|array',
                'content_blocks.services.title'     => 'nullable|string|max:80',
                'content_blocks.services.subtitle'  => 'nullable|string|max:200',
                'content_blocks.about'              => 'nullable|array',
                'content_blocks.about.title'        => 'nullable|string|max:80',
                'content_blocks.testimonials'       => 'nullable|array',
                'content_blocks.testimonials.title' => 'nullable|string|max:80',
                'content_blocks.testimonials.eyebrow' => 'nullable|string|max:60',
                'content_blocks.faq'                => 'nullable|array',
                'content_blocks.faq.title'          => 'nullable|string|max:80',
                'content_blocks.faq.eyebrow'        => 'nullable|string|max:60',
                'content_blocks.contact'            => 'nullable|array',
                'content_blocks.contact.title'      => 'nullable|string|max:80',
                'content_blocks.payment_methods'    => 'nullable|array',
                'content_blocks.payment_methods.title' => 'nullable|string|max:80',
                'content_blocks.branches'           => 'nullable|array',
                'content_blocks.branches.title'     => 'nullable|string|max:80',
                'content_blocks.branches.eyebrow'   => 'nullable|string|max:60',
                'about_text'                        => 'nullable|string|max:1000',
            ]);

            // Update tenant fields (excluding settings-only fields)
            $settingsOnlyKeys = ['contact_maps_url', 'contact_title', 'contact_subtitle', 'show_hours_indicator', 'closed_message'];
            $customizationOnlyKeys = ['content_blocks', 'about_text'];
            $tenant->update(collect($validated)->except(array_merge($settingsOnlyKeys, $customizationOnlyKeys))->toArray());

            // Save settings in settings JSON
            $settings = $tenant->settings ?? [];
            
            // Hours indicator feature (all plans)
            if ($request->has('show_hours_indicator')) {
                data_set($settings, 'engine_settings.features.show_hours_indicator', $validated['show_hours_indicator'] ?? false);
            }
            if ($request->has('closed_message')) {
                data_set($settings, 'business_info.closed_message', $validated['closed_message'] ?? 'Estamos cerrados. Te responderemos durante nuestro horario de atención.');
            }
            
            // contact_title y contact_subtitle: disponibles para todos los planes
            if ($request->has('contact_title')) {
                data_set($settings, 'business_info.contact.title', $validated['contact_title'] ?? '');
            }
            if ($request->has('contact_subtitle')) {
                data_set($settings, 'business_info.contact.subtitle', $validated['contact_subtitle'] ?? '');
            }

            // Contact settings extras (Plan 2+: maps)
            if ($tenant->isAtLeastCrecimiento()) {
                if ($request->has('contact_maps_url')) {
                    data_set($settings, 'business_info.contact.maps_url', $validated['contact_maps_url'] ?? '');
                }
            }
            
            $tenant->settings = $settings;
            $tenant->save();

            // Save content_blocks and explicit about_text to customization
            if ($tenant->customization) {
                $customizationData = [];

                if ($request->has('content_blocks')) {
                    // Deep-merge so plan-restricted keys (testimonials, faq) aren't wiped
                    $existing = $tenant->customization->content_blocks ?? [];
                    $incoming = $validated['content_blocks'] ?? [];
                    $customizationData['content_blocks'] = array_replace_recursive($existing, $incoming);
                }

                if ($request->has('about_text')) {
                    $customizationData['about_text'] = $validated['about_text'] ?? null;
                }

                if (!empty($customizationData)) {
                    $tenant->customization->update($customizationData);
                }
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
     * Update Header Top bar settings (Plan 2+).
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updateHeaderTop(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            if (!$tenant->isAtLeastCrecimiento()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header Top requiere Plan CRECIMIENTO o superior'
                ], 403);
            }

            $validated = $request->validate([
                'enabled' => 'required|boolean',
                'text' => 'nullable|string|max:120',
            ]);

            $settings = $tenant->settings ?? [];
            data_set($settings, 'engine_settings.header_top', [
                'enabled' => $validated['enabled'],
                'text' => $validated['text'] ?? '',
            ]);
            $tenant->settings = $settings;
            $tenant->save();

            return response()->json([
                'success' => true,
                'message' => 'Header Top actualizado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar Header Top: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update promotional header message stored in tenant_customization.
     */
    public function updateHeaderMessage(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->with('customization')
                ->firstOrFail();

            if (!$tenant->isAtLeastCrecimiento()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header Top requiere Plan CRECIMIENTO o superior'
                ], 403);
            }

            $validated = $request->validate([
                'header_message' => 'nullable|string|max:255',
            ]);

            $tenant->customization->update([
                'header_message' => $validated['header_message'] ?: null,
            ]);

            return response()->json(['success' => true, 'message' => 'Mensaje actualizado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Update CTA special section (Plan 3).
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updateCta(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->with('customization')
                ->firstOrFail();

            $validated = $request->validate([
                'cta_title' => 'nullable|string|max:100',
                'cta_subtitle' => 'nullable|string|max:200',
                'cta_button_text' => 'nullable|string|max:50',
                'cta_button_link' => 'nullable|url|max:500',
            ]);

            $tenant->customization->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'CTA actualizado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar CTA: ' . $e->getMessage()
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

            // Check blueprint-aware limits
            $maxItems = $tenant->getMaxItems();
            $currentCount = $tenant->products->count();

            if ($currentCount >= $maxItems) {
                $label = $tenant->getItemLabel();

                return response()->json([
                    'success' => false,
                    'message' => "Has alcanzado el límite de {$maxItems} {$label} en tu plan. Actualiza para agregar más."
                ], 422);
            }

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'nullable|string|max:500',
                'price_usd' => 'required|numeric|min:0',
                'compare_price_usd' => 'nullable|numeric|min:0',
                'badge' => 'nullable|in:popular,nuevo,promo,destacado',
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
                'message' => $tenant->getItemSingular() . ' creado',
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
                'compare_price_usd' => 'nullable|numeric|min:0',
                'badge' => 'nullable|in:popular,nuevo,promo,destacado',
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

            // Check blueprint-aware limits (fallback to plan limits)
            $maxServices = $tenant->plan->services_limit ?? 3;
            $blueprint = $tenant->getBlueprint();
            if ($blueprint) {
                $maxServices = (int) data_get($blueprint, "feature_limits.{$tenant->plan_id}.max_items", $maxServices);
            }

            if ($tenant->services->count() >= $maxServices) {
                return response()->json([
                    'success' => false,
                    'message' => "Has alcanzado el límite de {$maxServices} servicios en tu plan. Actualiza para agregar más."
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
                'data'    => $service,
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

            // Cuando se guarda con icon_name, limpiar image_filename (modo ícono anula imagen)
            if (!empty($validated['icon_name'])) {
                $validated['image_filename'] = null;
            }

            // Update service
            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Servicio actualizado',
                'data'    => $service,
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
     * Update tenant Preline theme.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updateTheme(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $planId = $tenant->plan_id ?? 1;
            $allowedThemes = PrelineThemeService::getThemesByPlan($planId);

            \Illuminate\Support\Facades\Log::info('[Theme] Update attempt', [
                'tenant_id'       => $tenantId,
                'plan_id'         => $planId,
                'requested_theme' => $request->input('theme_slug'),
                'allowed_themes'  => $allowedThemes,
            ]);

            // Clear custom palette when selecting a Preline theme
            $settings = $tenant->settings ?? [];
            if (isset($settings['engine_settings']['visual']['custom_palette'])) {
                unset($settings['engine_settings']['visual']['custom_palette']);
                $tenant->settings = $settings;
                $tenant->save();
            }

            // Validate with Rule::in (more robust than string concatenation)
            $validated = $request->validate([
                'theme_slug' => ['required', 'string', \Illuminate\Validation\Rule::in($allowedThemes)],
            ]);

            // Get or create customization record
            $customization = $tenant->customization;
            if (!$customization) {
                $customization = new \App\Models\TenantCustomization();
                $customization->tenant_id = $tenant->id;
            }

            // Update and persist
            $customization->theme_slug = $validated['theme_slug'];
            $customization->save();

            \Illuminate\Support\Facades\Log::info('[Theme] Saved successfully', [
                'tenant_id'        => $tenantId,
                'theme_slug'       => $customization->theme_slug,
                'customization_id' => $customization->id,
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Tema actualizado correctamente',
                'theme_slug' => $customization->theme_slug,
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Illuminate\Support\Facades\Log::warning('[Theme] Validation failed', [
                'tenant_id' => $tenantId,
                'input'     => $request->input('theme_slug'),
                'errors'    => $ve->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Tema no válido para tu plan',
                'errors'  => $ve->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[Theme] Update error', [
                'tenant_id' => $tenantId,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tema: ' . $e->getMessage(),
            ], 500);
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
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $planId = $tenant->plan_id ?? 1;
            $allowedThemes = PrelineThemeService::getThemesByPlan($planId);

            $validated = $request->validate([
                'theme' => ['required', 'string', \Illuminate\Validation\Rule::in($allowedThemes)],
            ]);

            // Save ONLY to customization->theme_slug (single source of truth)
            $customization = $tenant->customization;
            if (!$customization) {
                $customization = new \App\Models\TenantCustomization();
                $customization->tenant_id = $tenant->id;
            }
            $customization->theme_slug = $validated['theme'];
            $customization->save();

            // Clear legacy flyonui_theme from settings JSON if present
            $settings = $tenant->settings ?? [];
            if (isset($settings['engine_settings']['visual']['theme']['flyonui_theme'])) {
                unset($settings['engine_settings']['visual']['theme']['flyonui_theme']);
                $tenant->settings = $settings;
                $tenant->save();
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Tema actualizado correctamente',
                'theme_slug' => $validated['theme'],
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => 'Tema no válido para tu plan',
                'errors'  => $ve->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar paleta: ' . $e->getMessage(),
            ], 500);
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
            $symbol      = $request->input('symbol', 'REF');

            // Mapear display_mode a flags booleanos — euro_toggle es excluyente con both_toggle
            $showReference = in_array($displayMode, ['reference_only', 'both_toggle', 'euro_toggle']);
            $showBolivares = in_array($displayMode, ['bolivares_only', 'both_toggle', 'euro_toggle']);
            $showEuro      = $displayMode === 'euro_toggle';
            $hidePrice     = $displayMode === 'hidden';
            $hasToggle     = in_array($displayMode, ['both_toggle', 'euro_toggle']);

            $settings['engine_settings']['currency']['display']['show_reference'] = $showReference;
            $settings['engine_settings']['currency']['display']['show_bolivares'] = $showBolivares;
            $settings['engine_settings']['currency']['display']['show_euro']      = $showEuro;
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
     * Update testimonials (stored in settings['business_info']['testimonials']).
     */
    public function updateTestimonials(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)->where('status', 'active')->firstOrFail();

            $items = collect($request->input('testimonials', []))
                ->filter(fn($t) => !empty($t['name']) && !empty($t['text']))
                ->take(5)
                ->map(fn($t) => [
                    'name'   => strip_tags(trim($t['name'])),
                    'title'  => strip_tags(trim($t['title'] ?? '')),
                    'text'   => strip_tags(trim($t['text'])),
                    'rating' => min(5, max(1, (int)($t['rating'] ?? 5))),
                ])
                ->values()
                ->toArray();

            $settings = $tenant->settings ?? [];
            data_set($settings, 'business_info.testimonials', $items);
            $tenant->settings = $settings;
            $tenant->save();

            return response()->json(['success' => true, 'message' => 'Testimonios guardados']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Update FAQ (stored in settings['business_info']['faq']).
     */
    public function updateFaq(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::where('id', $tenantId)->where('status', 'active')->firstOrFail();

            $items = collect($request->input('faq', []))
                ->filter(fn($f) => !empty($f['question']) && !empty($f['answer']))
                ->take(5)
                ->map(fn($f) => [
                    'question' => strip_tags(trim($f['question'])),
                    'answer'   => strip_tags(trim($f['answer'])),
                ])
                ->values()
                ->toArray();

            $settings = $tenant->settings ?? [];
            data_set($settings, 'business_info.faq', $items);
            $tenant->settings = $settings;
            $tenant->save();

            return response()->json(['success' => true, 'message' => 'FAQ guardado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
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

            if (!$tenant->isVision()) {
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

            if (!$tenant->isVision()) {
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

            $allowedMethods    = [
                'pagoMovil', 'cash', 'puntoventa', 'biopago', 'cashea', 'krece',
                'wepa', 'lysto', 'chollo', 'wally', 'kontigo',
                'zelle', 'paypal', 'zinli', 'airtm', 'reserve', 'binancepay', 'usdt',
            ];
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

            // Log plan info for debugging
            \Log::info('saveCustomPalette', [
                'tenant_id' => $tenantId,
                'plan_id' => $tenant->plan_id,
                'is_vision' => $tenant->isVision()
            ]);

            if (!$tenant->isVision()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La paleta personalizada solo está disponible en el Plan Visión (plan_id=' . $tenant->plan_id . ')'
                ], 403);
            }

            // Guardar custom palette en settings
            $settings = $tenant->settings ?? [];
            $settings['engine_settings']['visual']['custom_palette'] = [
                'primary' => $request->primary,
            ];
            $tenant->settings = $settings;
            $tenant->save();

            // Marcar theme_slug como 'custom'
            $customization = $tenant->customization;
            if (!$customization) {
                $customization = new \App\Models\TenantCustomization();
                $customization->tenant_id = $tenant->id;
            }
            $customization->theme_slug = 'custom';
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

    /**
     * Update business hours (stored in tenant.business_hours JSON).
     */
    public function updateBusinessHours(Request $request, int $tenantId): JsonResponse
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $rules = [];
        foreach ($days as $day) {
            $rules["{$day}"]        = 'nullable|array';
            $rules["{$day}.closed"] = 'nullable|boolean';
            $rules["{$day}.open"]   = 'nullable|date_format:H:i';
            $rules["{$day}.close"]  = 'nullable|date_format:H:i';
        }

        $validated = $request->validate($rules);

        try {
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $hours = [];
            foreach ($days as $day) {
                $dayData = $validated[$day] ?? null;

                if (!is_array($dayData) || !empty($dayData['closed'])) {
                    $hours[$day] = null;
                    continue;
                }

                if (!empty($dayData['open']) && !empty($dayData['close'])) {
                    $hours[$day] = [
                        'open'  => $dayData['open'],
                        'close' => $dayData['close'],
                    ];
                    continue;
                }

                $hours[$day] = null;
            }

            $tenant->business_hours = $hours;
            $tenant->save();

            return response()->json(['success' => true, 'message' => 'Horario actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Toggle visibility of a single landing section.
     * Saves into customization.visual_effects.sections_order (same structure as saveSectionOrder).
     */
    public function toggleSection(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('customization')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            $validated = $request->validate([
                'section' => 'required|string|max:64',
                'visible' => 'required|boolean',
            ]);

            $customization = $tenant->customization
                ?? \App\Models\TenantCustomization::firstOrCreate(['tenant_id' => $tenantId]);

            $visualEffects  = $customization->visual_effects ?? [];
            $sectionsOrder  = $visualEffects['sections_order'] ?? [];

            // Update visibility for the given section; add it if missing
            $found = false;
            foreach ($sectionsOrder as &$item) {
                if ($item['name'] === $validated['section']) {
                    $item['visible'] = $validated['visible'];
                    $found = true;
                    break;
                }
            }
            unset($item);

            if (!$found) {
                $sectionsOrder[] = [
                    'name'    => $validated['section'],
                    'visible' => $validated['visible'],
                    'order'   => count($sectionsOrder),
                ];
            }

            $visualEffects['sections_order'] = $sectionsOrder;
            $customization->visual_effects   = $visualEffects;
            $customization->save();

            if (method_exists($customization, 'syncSectionsConfig')) {
                $customization->syncSectionsConfig();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 422);
        }
    }
}
