<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\DollarRateService;
use App\Services\QRService;
use App\Services\BusinessHoursService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class TenantRendererController extends Controller
{
    /**
     * @param DollarRateService $dollarRateService
     * @param QRService $qrService
     * @param BusinessHoursService $businessHoursService
     */
    public function __construct(
        private readonly DollarRateService $dollarRateService,
        private readonly QRService $qrService,
        private readonly BusinessHoursService $businessHoursService
    ) {}

    /**
     * Render tenant landing page by subdomain.
     *
     * @param string $subdomain
     * @return View|Response|JsonResponse
     */
    public function show(string $subdomain): View|Response|JsonResponse
    {
        try {
            // Normalize subdomain
            $subdomain = strtolower(trim($subdomain));

            // Find tenant by subdomain
            $tenant = Tenant::with([
                'plan',
                'customization',
                'products' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'products.galleryImages',
                'services' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'branches',
            ])
            ->where('subdomain', $subdomain)
            ->whereIn('status', ['active', 'frozen'])
            ->first();

            // Tenant not found or inactive
            if ($tenant === null) {
                Log::debug('TenantRendererController: Tenant not found', [
                    'subdomain' => $subdomain,
                ]);

                return $this->render404($subdomain);
            }

            // Frozen: subscription expired — show static freeze page
            if ($tenant->isFrozen()) {
                return response()->view('landing.frozen', [], 200);
            }

            $plan = $tenant->plan;

            // ═══════════════════════════════════════════════════════════════════════
            // FLYONUI THEME SYSTEM (Reemplaza ColorPalette custom)
            // ═══════════════════════════════════════════════════════════════════════
            // Prioridad: customization->theme_slug > settings JSON > 'light'
            // ═══════════════════════════════════════════════════════════════════════
            $customization = $tenant->customization;
            $themeSlug = $customization?->theme_slug
                ?? $tenant->settings['engine_settings']['visual']['theme']['flyonui_theme']
                ?? 'light';

            // Get current dollar rate
            $dollarRate = $this->dollarRateService->getCurrentRate();

            // Calculate price_bs for each product
            $products = $this->calculateProductPrices($tenant->products, $dollarRate);

            // Extract services
            $services = $tenant->services;

            Log::info('TenantRendererController: Rendering landing page', [
                'subdomain' => $subdomain,
                'tenant_id' => $tenant->id,
                'products_count' => $products->count(),
                'services_count' => $services->count(),
            ]);

            $meta = [
                'title' => $tenant->meta_title ?? $tenant->business_name,
                'description' => $tenant->meta_description ?? $tenant->description,
                'keywords' => $tenant->meta_keywords ?? '',
                'canonical' => url('/' . $tenant->subdomain),
                'og_title' => $tenant->meta_title ?? $tenant->business_name,
                'og_description' => $tenant->meta_description ?? $tenant->description,
                'og_image' => $tenant->logo_url ?? asset('images/default-og.jpg'),
                'og_url' => url('/' . $tenant->subdomain),
            ];

            // Extract currency display settings — lee siempre desde saved_display_mode
            $savedDisplayMode = data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only');
            $displayMode      = $savedDisplayMode;

            $showReference = in_array($savedDisplayMode, ['reference_only', 'both_toggle']);
            $showBolivares = in_array($savedDisplayMode, ['bolivares_only', 'both_toggle']);
            $hidePrice     = $savedDisplayMode === 'hidden';

            $currencySettings = [
                'show_conversion_button' => data_get($tenant->settings, 'engine_settings.currency.display.show_conversion_button', true),
                'mode' => $savedDisplayMode,
                'default_currency' => data_get($tenant->settings, 'engine_settings.currency.display.default_currency', 'REF'),
                'symbols' => data_get($tenant->settings, 'engine_settings.currency.display.symbols', ['reference' => 'REF', 'bolivares' => 'Bs.']),
            ];

            // Generate QR for traffic tracking in Floating Panel (private - only visible after PIN)
            $trackingQRSmall = $this->qrService->generateQR($tenant->id, 150);
            $trackingShortlink = $this->qrService->getTrackingShortlink($tenant->id);

            // Business hours indicator
            $showHoursIndicator = $this->businessHoursService->isHoursFeatureEnabled($tenant);
            $isOpen = $this->businessHoursService->isOpen($tenant);
            $closedMessage = data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.');

            // Blueprint system
            $blueprint = $tenant->getBlueprint();

            $schema = $this->buildSchema($tenant);

            return view('landing.base', compact('tenant', 'plan', 'products', 'services', 'dollarRate', 'themeSlug', 'meta', 'customization', 'currencySettings', 'displayMode', 'savedDisplayMode', 'showReference', 'showBolivares', 'hidePrice', 'trackingQRSmall', 'trackingShortlink', 'showHoursIndicator', 'isOpen', 'closedMessage', 'blueprint', 'schema'));
        } catch (Throwable $e) {
            Log::error('TenantRendererController: Error rendering landing page', [
                'subdomain' => $subdomain,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->renderError($e->getMessage());
        }
    }

    /**
     * Render tenant by custom domain.
     *
     * @param string $domain
     * @return View|Response|JsonResponse
     */
    public function showByDomain(string $domain): View|Response|JsonResponse
    {
        try {
            $domain = strtolower(trim($domain));

            $tenant = Tenant::with([
                'plan',
                'customization',
                'products' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position'),
                'products.galleryImages',
                'services' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position'),
                'branches',
            ])
            ->where('custom_domain', $domain)
            ->where('domain_verified', true)
            ->whereIn('status', ['active', 'frozen'])
            ->first();

            if ($tenant === null) {
                Log::debug('TenantRendererController: Tenant not found by domain', [
                    'domain' => $domain,
                ]);

                return $this->render404($domain);
            }

            // Frozen: subscription expired — show static freeze page
            if ($tenant->isFrozen()) {
                return response()->view('landing.frozen', [], 200);
            }

            // FlyonUI Theme System
            $customization = $tenant->customization;
            $themeSlug = $customization?->theme_slug
                ?? $tenant->settings['engine_settings']['visual']['theme']['flyonui_theme']
                ?? 'light';
            $dollarRate = $this->dollarRateService->getCurrentRate();
            $products = $this->calculateProductPrices($tenant->products, $dollarRate);
            $currencySettings = $this->extractCurrencySettings($tenant);

            $viewData = [
                'tenant' => $tenant,
                'plan' => $tenant->plan,
                'products' => $products,
                'services' => $tenant->services,
                'dollarRate' => $dollarRate,
                'themeSlug' => $themeSlug,
                'meta' => $this->buildMetaTags($tenant),
                'customization' => $customization,
                'currencySettings' => $currencySettings,
                'savedDisplayMode' => data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'),
                'displayMode' => data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'),
                'showReference' => in_array(data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'), ['reference_only', 'both_toggle']),
                'showBolivares' => in_array(data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'), ['bolivares_only', 'both_toggle']),
                'hidePrice' => data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only') === 'hidden',
                'blueprint' => $tenant->getBlueprint(),
            ];

            $viewData['schema'] = $this->buildSchema($tenant);

            Log::info('TenantRendererController: Rendering by custom domain', [
                'domain' => $domain,
                'tenant_id' => $tenant->id,
            ]);

            return view('landing.base', $viewData);
        } catch (Throwable $e) {
            Log::error('TenantRendererController: Error rendering by domain', [
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);

            return $this->renderError($e->getMessage());
        }
    }

    /**
     * Preview tenant (for admin/owner).
     *
     * @param int $tenantId
     * @return View|Response|JsonResponse
     */
    public function preview(int $tenantId): View|Response|JsonResponse
    {
        try {
            $tenant = Tenant::with([
                'plan',
                'customization',
                'products' => fn($q) => $q->orderBy('position'),
                'products.galleryImages',
                'services' => fn($q) => $q->orderBy('position'),
                'branches',
            ])->find($tenantId);

            if ($tenant === null) {
                return $this->render404("Tenant #{$tenantId}");
            }

            // FlyonUI Theme System
            $customization = $tenant->customization;
            $themeSlug = $customization?->theme_slug
                ?? $tenant->settings['engine_settings']['visual']['theme']['flyonui_theme']
                ?? 'light';
            $dollarRate = $this->dollarRateService->getCurrentRate();
            $products = $this->calculateProductPrices($tenant->products, $dollarRate);
            $currencySettings = $this->extractCurrencySettings($tenant);

            $viewData = [
                'tenant' => $tenant,
                'plan' => $tenant->plan,
                'products' => $products,
                'services' => $tenant->services,
                'dollarRate' => $dollarRate,
                'themeSlug' => $themeSlug,
                'meta' => $this->buildMetaTags($tenant),
                'customization' => $tenant->customization,
                'currencySettings' => $currencySettings,
                'savedDisplayMode' => data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'),
                'displayMode' => data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'),
                'showReference' => in_array(data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'), ['reference_only', 'both_toggle']),
                'showBolivares' => in_array(data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only'), ['bolivares_only', 'both_toggle']),
                'hidePrice' => data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only') === 'hidden',
                'blueprint' => $tenant->getBlueprint(),
                'isPreview' => true,
            ];

            $viewData['schema'] = $this->buildSchema($tenant);

            Log::debug('TenantRendererController: Preview mode', [
                'tenant_id' => $tenantId,
            ]);

            return view('landing.base', $viewData);
        } catch (Throwable $e) {
            Log::error('TenantRendererController: Error in preview', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->renderError($e->getMessage());
        }
    }

    /**
     * Build Schema.org LocalBusiness structured data.
     */
    private function buildSchema(Tenant $tenant): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => 'LocalBusiness',
            'name'     => $tenant->business_name,
            'description' => $tenant->description ?? '',
            'address'  => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $tenant->address ?? '',
                'addressLocality' => $tenant->city ?? '',
                'addressCountry'  => 'VE',
            ],
            'url' => url('/' . $tenant->subdomain),
        ];

        if ($tenant->customization?->logo_filename) {
            $schema['image'] = asset('storage/tenants/' . $tenant->id . '/' . $tenant->customization->logo_filename);
        }
        if ($tenant->phone) {
            $schema['telephone'] = $tenant->phone;
        }
        if ($tenant->email) {
            $schema['email'] = $tenant->email;
        }
        if ($tenant->whatsapp_sales) {
            $schema['potentialAction'] = [
                '@type'  => 'CommunicateAction',
                'target' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales),
            ];
        }

        return $schema;
    }

    /**
     * Calculate price_bs for each product.
     *
     * @param \Illuminate\Database\Eloquent\Collection $products
     * @param float $dollarRate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function calculateProductPrices($products, float $dollarRate)
    {
        return $products->map(function ($product) use ($dollarRate) {
            $product->price_bs_calculated = $product->price_usd
                ? round((float) $product->price_usd * $dollarRate, 2)
                : null;
            $product->exchange_rate = $dollarRate;
            return $product;
        });
    }

    /**
     * Extract currency display settings.
     *
     * @param Tenant $tenant
     * @return array
     */
    private function extractCurrencySettings(Tenant $tenant): array
    {
        $settings = $tenant->settings ?? [];
        $currencyConfig = data_get($settings, 'engine_settings.currency', []);
        $displayConfig = data_get($currencyConfig, 'display', []);

        return [
            'mode' => $displayConfig['mode'] ?? 'toggle',
            'default_currency' => $displayConfig['default_currency'] ?? 'REF',
            'show_conversion_button' => $displayConfig['show_conversion_button'] ?? true,
            'symbols' => [
                'reference' => $displayConfig['symbols']['reference'] ?? 'REF',
                'bolivares' => $displayConfig['symbols']['bolivares'] ?? 'Bs.',
            ],
            'decimals' => $displayConfig['decimals'] ?? 2,
            'exchange_rate' => $currencyConfig['exchange_rate'] ?? null,
            'last_update' => $currencyConfig['last_update'] ?? null,
        ];
    }

    /**
     * Build meta tags for SEO.
     *
     * @param Tenant $tenant
     * @return array
     */
    private function buildMetaTags(Tenant $tenant): array
    {
        $businessName = $tenant->business_name;

        return [
            'title' => $tenant->meta_title ?? $businessName,
            'description' => $tenant->meta_description ?? $tenant->description ?? "Bienvenido a {$businessName}",
            'keywords' => $tenant->meta_keywords ?? $tenant->business_segment ?? '',
            'og_title' => $tenant->meta_title ?? $businessName,
            'og_description' => $tenant->meta_description ?? $tenant->slogan ?? '',
            'og_image' => $tenant->customization?->hero_main_filename ?? null,
            'canonical' => $this->buildCanonicalUrl($tenant),
        ];
    }

    /**
     * Build canonical URL for tenant.
     *
     * @param Tenant $tenant
     * @return string
     */
    private function buildCanonicalUrl(Tenant $tenant): string
    {
        if ($tenant->custom_domain && $tenant->domain_verified) {
            return "https://{$tenant->custom_domain}";
        }

        $baseDomain = $tenant->base_domain ?? 'menu.vip';
        return "https://{$tenant->subdomain}.{$baseDomain}";
    }

    /**
     * Get default exchange rate from tenant settings.
     *
     * @param Tenant $tenant
     * @return float
     */
    private function getDefaultRate(Tenant $tenant): float
    {
        $settings = $tenant->settings ?? [];
        return (float) data_get($settings, 'engine_settings.currency.exchange_rate', 36.50);
    }

    /**
     * Render 404 page.
     *
     * @param string $identifier
     * @return Response
     */
    private function render404(string $identifier): Response
    {
        return response()->view('errors.tenant-not-found', [
            'identifier' => $identifier,
        ], 404);
    }

    /**
     * Render error page.
     *
     * @param string $message
     * @return JsonResponse
     */
    private function renderError(string $message = 'Server Error'): JsonResponse
    {
        return response()->json([
            'error' => $message,
            'tenant' => 'Error loading tenant'
        ], 500);
    }

    /**
     * Verify tenant PIN.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function verifyPin(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Get PIN from request
            $pin = $request->input('pin');

            // Verify PIN
            if (Hash::check($pin, $tenant->edit_pin)) {
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'message' => 'PIN incorrecto'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar PIN'
            ], 500);
        }
    }

    /**
     * Get business hours status (AJAX endpoint)
     * 
     * Route: GET /tenant/{id}/hours/status
     * 
     * @param int $tenantId
     * @return JsonResponse
     */
    public function getHoursStatus(int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);
            
            $showIndicator = $this->businessHoursService->isHoursFeatureEnabled($tenant);
            $isOpen = $this->businessHoursService->isOpen($tenant);
            $nextOpen = !$isOpen ? $this->businessHoursService->getNextOpenTime($tenant) : null;
            $closedMessage = data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.');
            
            return response()->json([
                'success' => true,
                'is_open' => $isOpen,
                'next_open' => $nextOpen,
                'show_indicator' => $showIndicator,
                'closed_message' => $closedMessage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching hours status'
            ], 500);
        }
    }

    /**
     * Toggle tenant open/closed status.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function toggleStatus(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Find tenant
            $tenant = Tenant::findOrFail($tenantId);

            // Toggle status
            $tenant->is_open = !$tenant->is_open;
            $tenant->save();

            return response()->json([
                'success' => true,
                'is_open' => $tenant->is_open
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado'
            ], 500);
        }
    }
}
