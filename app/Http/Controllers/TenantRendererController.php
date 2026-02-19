<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\DollarRateService;
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
     */
    public function __construct(
        private readonly DollarRateService $dollarRateService
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
                'colorPalette',
                'customization',
                'products' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'services' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
            ])
            ->where('subdomain', $subdomain)
            ->where('status', 'active')
            ->first();

            // Tenant not found or inactive
            if ($tenant === null) {
                Log::debug('TenantRendererController: Tenant not found', [
                    'subdomain' => $subdomain,
                ]);

                return $this->render404($subdomain);
            }

            $plan = $tenant->plan;

            // Obtener paleta de colores
            $paletteCode = data_get($tenant->settings, 'engine_settings.visual.theme.palette_code', 'energia-roja');
            $palette = \App\Models\ColorPalette::where('code', $paletteCode)->first();

            if (!$palette) {
                $palette = \App\Models\ColorPalette::where('code', 'energia-roja')->first();
            }

            $colors = [
                'primary' => $palette->primary_color,
                'secondary' => $palette->secondary_color,
                'accent' => $palette->accent_color,
                'text' => $palette->text_color,
                'textMuted' => $palette->text_muted,
                'background' => $palette->background_color,
                'backgroundAlt' => $palette->background_alt,
                'buttonBg' => $palette->button_bg,
                'buttonText' => $palette->button_text,
                'buttonHoverBg' => $palette->button_hover_bg,
                'linkColor' => $palette->link_color,
                'linkHover' => $palette->link_hover,
                'header_bg' => $palette->primary_color,
                'header_text' => $this->getContrastColor($palette->primary_color),
                'section_bg' => $palette->background_color ?? '#FFFFFF',
                'section_bg_alt' => $palette->background_alt ?? '#F5F5F5',
                'footer_bg' => $palette->background_alt ?? '#1a1a1a',
                'footer_text' => $this->getContrastColor($palette->background_alt ?? '#1a1a1a'),
                'footer_text_muted' => $this->getContrastColor($palette->background_alt ?? '#1a1a1a'),
            ];

            $fonts = [
                'heading' => $palette->font_primary ?? 'Inter',
                'body' => $palette->font_secondary ?? 'Inter',
            ];

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

            $customization = $tenant->customization;

            // Extract currency display settings
            $displayMode = data_get($tenant->settings, 'engine_settings.currency.display.mode', 'reference_only');

            // Leer display_mode guardado por updateCurrencyConfig
            $savedDisplayMode = data_get($tenant->settings, 'engine_settings.currency.display.saved_display_mode', 'reference_only');

            $showReference = in_array($savedDisplayMode, ['reference_only', 'both_toggle']);
            $showBolivares = in_array($savedDisplayMode, ['bolivares_only', 'both_toggle']);
            $hidePrice     = $savedDisplayMode === 'hidden';

            $currencySettings = [
                'show_conversion_button' => data_get($tenant->settings, 'engine_settings.currency.display.show_conversion_button', true),
                'mode' => data_get($tenant->settings, 'engine_settings.currency.display.mode', 'toggle'),
                'default_currency' => data_get($tenant->settings, 'engine_settings.currency.display.default_currency', 'REF'),
                'symbols' => data_get($tenant->settings, 'engine_settings.currency.display.symbols', ['reference' => 'REF', 'bolivares' => 'Bs.']),
            ];

            return view('landing.base', compact('tenant', 'plan', 'products', 'services', 'dollarRate', 'colors', 'fonts', 'meta', 'customization', 'currencySettings', 'displayMode', 'showReference', 'showBolivares', 'hidePrice'));
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
                'colorPalette',
                'customization',
                'products' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position'),
                'services' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position'),
            ])
            ->where('custom_domain', $domain)
            ->where('domain_verified', true)
            ->where('status', 'active')
            ->first();

            if ($tenant === null) {
                Log::debug('TenantRendererController: Tenant not found by domain', [
                    'domain' => $domain,
                ]);

                return $this->render404($domain);
            }

            // Reuse show logic
            $dollarRate = $this->dollarRateService->getCurrentRate();
            $products = $this->calculateProductPrices($tenant->products, $dollarRate);
            $themeColors = $this->extractThemeColors($tenant);
            $currencySettings = $this->extractCurrencySettings($tenant);

            $viewData = [
                'tenant' => $tenant,
                'products' => $products,
                'services' => $tenant->services,
                'dollarRate' => $dollarRate,
                'themeColors' => $themeColors,
                'currencySettings' => $currencySettings,
                'plan' => $tenant->plan,
                'customization' => $tenant->customization,
                'colorPalette' => $tenant->colorPalette,
                'meta' => $this->buildMetaTags($tenant),
            ];

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
                'colorPalette',
                'customization',
                'products' => fn($q) => $q->orderBy('position'),
                'services' => fn($q) => $q->orderBy('position'),
            ])->find($tenantId);

            if ($tenant === null) {
                return $this->render404("Tenant #{$tenantId}");
            }

            $dollarRate = $this->dollarRateService->getCurrentRate();
            $products = $this->calculateProductPrices($tenant->products, $dollarRate);
            $themeColors = $this->extractThemeColors($tenant);
            $currencySettings = $this->extractCurrencySettings($tenant);

            $viewData = [
                'tenant' => $tenant,
                'products' => $products,
                'services' => $tenant->services,
                'dollarRate' => $dollarRate,
                'themeColors' => $themeColors,
                'currencySettings' => $currencySettings,
                'plan' => $tenant->plan,
                'customization' => $tenant->customization,
                'colorPalette' => $tenant->colorPalette,
                'meta' => $this->buildMetaTags($tenant),
                'isPreview' => true,
            ];

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
     * Calculate contrast color (black or white) based on background luminosity.
     * Uses WCAG relative luminance formula.
     *
     * @param string $hexColor
     * @return string
     */
    private function getContrastColor(string $hexColor): string
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        
        // Apply gamma correction
        $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
        
        // Calculate relative luminance (WCAG formula)
        $luminance = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
        
        // Return dark or light color based on luminance
        return $luminance > 0.5 ? '#1a1a1a' : '#FFFFFF';
    }

    /**
     * Extract theme colors from tenant settings or color palette.
     *
     * @param Tenant $tenant
     * @return array
     */
    private function extractThemeColors(Tenant $tenant): array
    {
        $settings = $tenant->settings ?? [];
        $palette = $tenant->colorPalette;

        // Try to get from settings first
        $visualSettings = data_get($settings, 'engine_settings.visual', []);

        // Calculate footer background
        $footerBg = $visualSettings['footer_bg'] ?? $palette?->background_alt ?? '#1a1a1a';
        $footerText = $this->getContrastColor($footerBg);

        // Fallback to color palette
        $headerBg = $visualSettings['header_bg'] ?? $palette?->primary_color ?? '#0066CC';
        $headerText = $this->getContrastColor($headerBg);
        
        return [
            'primary' => $visualSettings['primary_color'] ?? $palette?->primary_color ?? '#0066CC',
            'secondary' => $visualSettings['secondary_color'] ?? $palette?->secondary_color ?? '#FFFFFF',
            'accent' => $visualSettings['accent_color'] ?? $palette?->accent_color ?? '#FF6600',
            'background' => $visualSettings['background_color'] ?? $palette?->background_color ?? '#FFFFFF',
            'text' => $visualSettings['text_color'] ?? $palette?->text_color ?? '#000000',
            'header_bg' => $headerBg,
            'header_text' => $headerText,
            'section_bg' => $visualSettings['background_color'] ?? $palette?->background_color ?? '#FFFFFF',
            'section_bg_alt' => $visualSettings['background_alt'] ?? $palette?->background_alt ?? '#F5F5F5',
            'footer_bg' => $footerBg,
            'footer_text' => $footerText,
            'footer_text_muted' => $footerText,
            'button_bg' => $visualSettings['button_bg'] ?? $palette?->primary_color ?? '#0066CC',
            'button_text' => $visualSettings['button_text'] ?? '#FFFFFF',
        ];
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
            'og_image' => $tenant->customization?->hero_filename ?? null,
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
