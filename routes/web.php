<?php
// C:\laragon\www\synticorex\routes\web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\TenantRendererController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\QRTrackingController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\TenantsController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SyntiHelpController;
use App\Services\DollarRateService;

// ═══ Marketing Landing Page (root domain) ═══════════════════════════════
Route::get('/', [MarketingController::class, 'index'])->name('home');

// Rutas de auth siempre disponibles (cualquier host), necesario en entorno local.
// En producción están alojadas en app.synticorex.test pero no hay conflicto
// registrarlas globalmente — son rutas de back-office, no de landing.
require __DIR__ . '/auth.php';

Route::domain('app.synticorex.test')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', fn () => redirect()->route('tenants.index'))->name('dashboard');
    });
});

// ═══ Landings de producto ════════════════════════════════════════════════════
Route::get('/planes', [MarketingController::class, 'planes'])->name('marketing.planes');
Route::get('/studio', [MarketingController::class, 'studio'])->name('marketing.studio');
Route::get('/food',   [MarketingController::class, 'food'])->name('marketing.food');
Route::get('/cat',    [MarketingController::class, 'cat'])->name('marketing.cat');
Route::get('/terminos', [MarketingController::class, 'terms'])->name('marketing.terms');
Route::get('/privacidad', [MarketingController::class, 'privacy'])->name('marketing.privacy');
Route::get('/nosotros', [MarketingController::class, 'about'])->name('marketing.about');
Route::get('/contacto', [MarketingController::class, 'contacto'])->name('marketing.contacto');
Route::get('/demos',    [MarketingController::class, 'demos'])->name('marketing.demos');

// ═══ Google OAuth ═════════════════════════════════════════════════════════════
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// ═══ Onboarding selector ═════════════════════════════════════════════════════
Route::get('/onboarding', [OnboardingController::class, 'selector'])->name('onboarding.selector');

// ═══ Onboarding Wizard (requiere autenticación) ══════════════════════════════
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding/nuevo', [OnboardingController::class, 'index'])
         ->name('onboarding.index');
    Route::post('/onboarding/guardar', [OnboardingController::class, 'store'])
         ->name('onboarding.store');
    Route::get('/onboarding/subdomain-check', [OnboardingController::class, 'checkSubdomain'])
         ->name('onboarding.subdomain-check');
    Route::get('/onboarding/{tenant}/preview', [OnboardingController::class, 'preview'])
         ->name('onboarding.preview');
    Route::post('/onboarding/{tenant}/publicar', [OnboardingController::class, 'publish'])
         ->name('onboarding.publish');

    // ═══ Wizards por producto ═════════════════════════════════════════════════
    Route::get('/onboarding/studio',  [OnboardingController::class, 'index'])->name('onboarding.studio');
    Route::get('/onboarding/food',    [OnboardingController::class, 'food'])->name('onboarding.food');
    Route::get('/onboarding/cat',     [OnboardingController::class, 'cat'])->name('onboarding.cat');

    // ═══ Stores por producto ══════════════════════════════════════════════════
    Route::post('/onboarding/studio/guardar', [OnboardingController::class, 'store'])->name('onboarding.store.studio');
    Route::post('/onboarding/food/guardar',   [OnboardingController::class, 'storeFood'])->name('onboarding.store.food');
    Route::post('/onboarding/cat/guardar',    [OnboardingController::class, 'storeCat'])->name('onboarding.store.cat');
});

// ═══════════════════════════════════════════════════════════════════════════════
// DASHBOARD AUTHENTICATED (MUST be before /{subdomain} catch-all)
// ═══════════════════════════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified'])->group(function () {
    // My Tenants listing
    Route::get('/mis-negocios', [TenantsController::class, 'index'])->name('tenants.index');
});

// ═══ Blog público ════════════════════════════════════════════════════════════
Route::get('/blog', [MarketingController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [MarketingController::class, 'blogPost'])
    ->where('slug', '[a-z0-9-]+')
    ->name('blog.show');

// Landing page pública por subdomain
Route::middleware('tenant')->get('/{subdomain}', [TenantRendererController::class, 'show'])
    ->where('subdomain', '[a-z0-9-]+')
    ->name('tenant.landing');

// ═══ Tenant panel — acciones públicas (protegidas por PIN, no por auth) ═════
Route::post('/tenant/{tenantId}/verify-pin',    [TenantRendererController::class, 'verifyPin'])
    ->middleware('throttle:5,1');
Route::post('/tenant/{tenantId}/toggle-status', [TenantRendererController::class, 'toggleStatus']);
Route::patch('/tenant/{tenantId}/toggle-whatsapp', [TenantRendererController::class, 'toggleWhatsapp']);

// ═══ APIs públicas ════════════════════════════════════════════════════════════
Route::post('/api/analytics/track', [AnalyticsController::class, 'track']);

// Retorna la tasa actual desde cache/BD (lectura)
Route::get('/api/dollar-rate', function (DollarRateService $service) {
    return response()->json(['success' => true, 'rate' => $service->getCurrentRate()]);
});
Route::get('/api/euro-rate', function (DollarRateService $service) {
    return response()->json(['success' => true, 'rate' => $service->getCurrentEuroRate()]);
});

// Actualizar tasa desde DolarAPI y propagarla a tenants (escribe en BD — solo admin)
Route::middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
    Route::post('/api/dollar-rate/refresh', function (DollarRateService $service) {
        $result = $service->fetchAndStore();
        if ($result['success']) {
            $service->propagateRateToTenants($result['rate']);
        }
        return response()->json($result);
    });
    Route::post('/api/euro-rate/refresh', function (DollarRateService $service) {
        $result = $service->fetchAndStoreEuro();
        if ($result['success']) {
            $service->propagateEuroRateToTenants($result['rate']);
        }
        return response()->json($result);
    });
});

// ═══════════════════════════════════════════════════════════════════════════════
// DASHBOARD AUTHENTICATED (continued — uploads, CRUD, etc.)
// ═══════════════════════════════════════════════════════════════════════════════

Route::middleware(['auth', 'verified', 'tenant.owner:tenantId'])->group(function () {
    // Image upload
    Route::prefix('tenant/{tenantId}/upload')->group(function () {
        Route::post('/logo',                    [ImageUploadController::class, 'uploadLogo']);
        Route::post('/hero',                    [ImageUploadController::class, 'uploadHero']);
        Route::post('/hero-slot/{slot}',        [ImageUploadController::class, 'uploadHeroSlot'])->where('slot', '[1-5]');
        Route::delete('/hero-slot/{slot}',      [ImageUploadController::class, 'deleteHeroSlot'])->where('slot', '[1-5]');
        Route::post('/product/{productId}',     [ImageUploadController::class, 'uploadProduct']);
        Route::post('/service/{serviceId}',     [ImageUploadController::class, 'uploadService']);
        Route::post('/about',                    [ImageUploadController::class, 'uploadAbout']);
        // Gallery images (Plan 3 / VISIÓN only)
        Route::post('/product/{productId}/gallery',              [ImageUploadController::class, 'uploadProductGallery']);
        Route::delete('/product/{productId}/gallery/{imageId}',  [ImageUploadController::class, 'deleteProductGalleryImage']);
    });

    // Tenant dashboard
    Route::get('/tenant/{tenantId}/dashboard',       [DashboardController::class, 'index'])->name('dashboard.edit-tenant');
    Route::post('/tenant/{tenantId}/update-info',    [DashboardController::class, 'updateInfo']);
    Route::post('/tenant/{tenantId}/update-theme',   [DashboardController::class, 'updateTheme']);
    Route::post('/tenant/{tenantId}/update-palette', [DashboardController::class, 'updatePalette']); // Legacy

    // Products CRUD
    Route::post('/tenant/{tenantId}/products',               [DashboardController::class, 'createProduct']);
    Route::put('/tenant/{tenantId}/products/{productId}',    [DashboardController::class, 'updateProduct']);
    Route::delete('/tenant/{tenantId}/products/{productId}', [DashboardController::class, 'deleteProduct']);

    // CAT Categories CRUD
    Route::get('/tenant/{tenantId}/cat-categories',                          [DashboardController::class, 'getCatCategories']);
    Route::post('/tenant/{tenantId}/cat-categories',                         [DashboardController::class, 'createCatCategory']);
    Route::delete('/tenant/{tenantId}/cat-categories/{categoryId}',          [DashboardController::class, 'deleteCatCategory']);

    // Services CRUD
    Route::post('/tenant/{tenantId}/services',               [DashboardController::class, 'createService']);
    Route::put('/tenant/{tenantId}/services/{serviceId}',    [DashboardController::class, 'updateService']);
    Route::delete('/tenant/{tenantId}/services/{serviceId}', [DashboardController::class, 'deleteService']);

    // Branches (Plan 3 / VISIÓN)
    Route::post('/tenant/{tenantId}/branches/toggle',             [DashboardController::class, 'toggleBranches']);
    Route::post('/tenant/{tenantId}/branches',                    [DashboardController::class, 'saveBranch']);
    Route::delete('/tenant/{tenantId}/branches/{branchId}',       [DashboardController::class, 'deleteBranch']);

    // Payment Methods
    Route::post('/tenant/{tenantId}/update-payment-methods',      [DashboardController::class, 'updatePaymentMethods']);

    // Social Networks
    Route::post('/tenant/{tenantId}/update-social-networks',      [DashboardController::class, 'updateSocialNetworks']);

    // Section Order (drag & drop)
    Route::post('/tenant/{tenantId}/dashboard/save-section-order', [DashboardController::class, 'saveSectionOrder'])
        ->name('tenant.dashboard.save-section-order');

    // Custom palette (Plan 3)
    Route::post('/tenant/{tenantId}/dashboard/save-custom-palette', [DashboardController::class, 'saveCustomPalette'])
        ->name('tenant.dashboard.save-custom-palette');

    Route::post('/tenant/{tenantId}/dashboard/toggle-section', [DashboardController::class, 'toggleSection'])
        ->name('tenant.dashboard.toggle-section');

    // Config actions
    Route::post('/tenant/{tenantId}/update-currency-config',      [DashboardController::class, 'updateCurrencyConfig']);
    Route::post('/tenant/{tenantId}/update-pin',                  [DashboardController::class, 'updatePin']);
    Route::post('/tenant/{tenantId}/update-testimonials',         [DashboardController::class, 'updateTestimonials']);
    Route::post('/tenant/{tenantId}/update-faq',                  [DashboardController::class, 'updateFaq']);
    Route::post('/tenant/{tenantId}/update-header-top',           [DashboardController::class, 'updateHeaderTop']);
    Route::post('/tenant/{tenantId}/update-header-message',        [DashboardController::class, 'updateHeaderMessage']);
    Route::post('/tenant/{tenantId}/update-cta',                  [DashboardController::class, 'updateCta']);
    Route::post('/tenant/{tenantId}/update-business-hours',       [DashboardController::class, 'updateBusinessHours']);

    // Comandas JSON (auto-refresh)
    Route::get('/tenant/{tenantId}/comandas-json',                [DashboardController::class, 'getComandasJson']);
    Route::post('/tenant/{tenantId}/comandas/{comandaId}/action', [DashboardController::class, 'updateComandaAction'])
        ->where('comandaId', '[A-Z0-9-]+');
    Route::post('/tenant/{tenantId}/orders/{orderId}/action', [DashboardController::class, 'updateOrderAction'])
        ->where('orderId', '[A-Z0-9-]+');

    // Analytics (datos del tenant — privado)
    Route::get('/tenant/{tenantId}/analytics',                    [AnalyticsController::class, 'getData']);
	Route::get('/tenant/{tenantId}/analytics/today',              [AnalyticsController::class, 'getToday']);
    // QR download
    Route::get('/tenant/{tenantId}/qr/download',                  [QRTrackingController::class, 'downloadQR']);

    // ── Billing / Facturación SYNTIweb ───────────────────────────────
    Route::get('/tenant/{tenantId}/billing',                      [BillingController::class, 'getBillingData'])->name('tenant.billing.data');
    Route::post('/tenant/{tenantId}/billing/report-payment',      [BillingController::class, 'reportPayment'])->name('tenant.billing.report');

}); // end middleware(['auth', 'verified', 'tenant.owner:tenantId'])

// ═══ Admin — Cola de revisión de pagos ═══════════════════════════════════════
Route::middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->prefix('admin')->group(function () {
    Route::get('/stats-badge', function () {
        return response()->json([
            'active'           => \App\Models\Tenant::where('status', 'active')->count(),
            'pending_payments' => \App\Models\Invoice::where('status', 'pending_review')->count(),
            'open_tickets'     => \App\Models\SupportTicket::where('status', 'open')->count(),
        ]);
    })->name('admin.stats-badge');

    Route::get('/billing',                          [BillingController::class, 'adminQueue'])->name('admin.billing.queue');
    Route::get('/billing/view',                     fn () => view('admin.billing'))->name('admin.billing.view');
    Route::post('/billing/{invoiceId}/approve',     [BillingController::class, 'approvePayment'])->name('admin.billing.approve');
    Route::post('/billing/{invoiceId}/reject',      [BillingController::class, 'rejectPayment'])->name('admin.billing.reject');
    Route::get('/billing/{invoiceId}/receipt',       [BillingController::class, 'viewReceipt'])->name('admin.billing.receipt');

    Route::post('/health/refresh', [\App\Http\Controllers\Admin\HealthCheckController::class, 'refresh'])->name('admin.health.refresh');

    // ═══ Admin Tools ═════════════════════════════════════════════════════
    Route::post('/tools/cache', [\App\Http\Controllers\Admin\AdminToolsController::class, 'clearCache'])->name('admin.tools.cache');
    Route::post('/tools/logs', [\App\Http\Controllers\Admin\AdminToolsController::class, 'clearLogs'])->name('admin.tools.logs');
    Route::post('/tools/queue', [\App\Http\Controllers\Admin\AdminToolsController::class, 'restartQueue'])->name('admin.tools.queue');
    Route::post('/tools/migrate', [\App\Http\Controllers\Admin\AdminToolsController::class, 'runMigrations'])->name('admin.tools.migrate');
    Route::post('/tools/suspend-expired', [\App\Http\Controllers\Admin\AdminToolsController::class, 'suspendExpiredNow'])->name('admin.tools.suspend');
    Route::post('/tools/reindex', [\App\Http\Controllers\Admin\AdminToolsController::class, 'reindexAiDocs'])->name('admin.tools.reindex');
    Route::get('/tools/disk', [\App\Http\Controllers\Admin\AdminToolsController::class, 'getDiskUsage'])->name('admin.tools.disk');
    Route::get('/tools/log-tail', [\App\Http\Controllers\Admin\AdminToolsController::class, 'getLogTail'])->name('admin.tools.log-tail');
    Route::post('/tools/test-report/{tenantId}', [\App\Http\Controllers\Admin\AdminToolsController::class, 'sendTestReport'])->name('admin.tools.test-report');
});

// ═══ Mini Order Engine — SYNTIcat ════════════════════════════════════════════
Route::post('/{subdomain}/checkout', [CheckoutController::class, 'store'])
    ->middleware(['web'])
    ->where('subdomain', '[a-z0-9-]+')
    ->name('tenant.checkout');

// Orders dashboard (auth required)
Route::middleware(['auth', 'tenant.owner:tenantId'])->group(function () {
    Route::get('/tenant/{tenantId}/orders', [OrdersController::class, 'index'])->name('tenant.orders');
});

// ═══ SYNTIfood Comanda Engine ═════════════════════════════════════════════════
Route::post('/{subdomain}/food-checkout', [\App\Http\Controllers\Food\ComandaController::class, 'store'])
    ->middleware(['web'])
    ->where('subdomain', '[a-z0-9-]+')
    ->name('food.checkout');

// ═══ SYNTIfood Menu Engine ═══════════════════════════════════════════════════
Route::middleware(['auth', 'tenant.owner:tenantId'])->prefix('tenant/{tenantId}/food')->group(function () {
    Route::apiResource('categories', \App\Http\Controllers\Food\CategoriesController::class)->except(['show']);
    Route::apiResource('categories.items', \App\Http\Controllers\Food\ItemsController::class)->except(['show']);
});
Route::get('/menu/{subdomain}', [\App\Http\Controllers\Food\MenuController::class, 'show'])
    ->where('subdomain', '[a-z0-9-]+')
    ->name('food.menu.public');

// ═══ PWA Manifest — Dynamic per tenant ═══════════════════════════════════════
Route::get('/manifest/{subdomain}.json', function (string $subdomain) {
    $tenant = \App\Models\Tenant::where('subdomain', $subdomain)
        ->with(['customization'])
        ->first();
    if (!$tenant || empty($tenant->subdomain)) abort(404);

    $logo = $tenant->customization?->logo_filename
        ? asset('storage/tenants/' . $tenant->id . '/' . $tenant->customization->logo_filename)
        : asset('brand/android-chrome-512x512.png');

    return response()->json([
        'name'             => $tenant->business_name,
        'short_name'       => \Str::limit($tenant->business_name, 12),
        'description'      => $tenant->slogan ?? $tenant->business_name,
        'start_url'        => '/' . $tenant->subdomain,
        'scope'            => '/' . $tenant->subdomain,
        'display'          => 'standalone',
        'orientation'      => 'portrait',
        'background_color' => '#ffffff',
        'theme_color'      => $tenant->customization?->primary_color ?? '#4A80E4',
        'icons'            => [
            ['src' => $logo, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ['src' => $logo, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
    ])->header('Content-Type', 'application/manifest+json')
      ->header('Cache-Control', 'public, max-age=3600');
})->middleware('web');

// ═══ SYNTiA — Asistente IA (requiere sesión web) ═════════════════════════════
Route::middleware(['auth', 'throttle:30,1'])->prefix('api/synti')->group(function () {
    Route::post('/ask',      [SyntiHelpController::class, 'ask']);
    Route::post('/feedback', [SyntiHelpController::class, 'feedback']);
});

// ═══ SEO — Sitemap y Robots ═══════════════════════════════════════════════════
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

