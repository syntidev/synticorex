<?php
// C:\laragon\www\synticorex\routes\web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\TenantRendererController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\QRTrackingController;
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
        Route::get('/dashboard', function () {
            return view('dashboard'); // Volveremos al dashboard original por ahora
        })->name('dashboard');
    });
});

// Landing page pública por subdomain
Route::middleware('tenant')->get('/{subdomain}', [TenantRendererController::class, 'show'])
    ->where('subdomain', '[a-z0-9-]+')
    ->name('tenant.landing');

// ═══ Tenant panel — acciones públicas (protegidas por PIN, no por auth) ═════
Route::post('/tenant/{tenantId}/verify-pin',    [TenantRendererController::class, 'verifyPin']);
Route::post('/tenant/{tenantId}/toggle-status', [TenantRendererController::class, 'toggleStatus']);

// ═══ APIs públicas ════════════════════════════════════════════════════════════
Route::post('/api/analytics/track', [AnalyticsController::class, 'track']);

// Retorna la tasa actual desde cache/BD (lectura)
Route::get('/api/dollar-rate', function (DollarRateService $service) {
    return response()->json(['success' => true, 'rate' => $service->getCurrentRate()]);
});
Route::get('/api/euro-rate', function (DollarRateService $service) {
    return response()->json(['success' => true, 'rate' => $service->getCurrentEuroRate()]);
});

// Actualizar tasa desde DolarAPI y propagarla a tenants (escribe en BD)
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

// ═══ Dashboard — requiere autenticación ══════════════════════════════════════
Route::middleware(['auth'])->group(function () {

    // Image upload
    Route::prefix('tenant/{tenantId}/upload')->group(function () {
        Route::post('/logo',                    [ImageUploadController::class, 'uploadLogo']);
        Route::post('/hero',                    [ImageUploadController::class, 'uploadHero']);
        Route::post('/product/{productId}',     [ImageUploadController::class, 'uploadProduct']);
        Route::post('/service/{serviceId}',     [ImageUploadController::class, 'uploadService']);
        // Gallery images (Plan 3 / VISIÓN only)
        Route::post('/product/{productId}/gallery',              [ImageUploadController::class, 'uploadProductGallery']);
        Route::delete('/product/{productId}/gallery/{imageId}',  [ImageUploadController::class, 'deleteProductGalleryImage']);
    });

    // Tenant dashboard
    Route::get('/tenant/{tenantId}/dashboard',       [DashboardController::class, 'index']);
    Route::post('/tenant/{tenantId}/update-info',    [DashboardController::class, 'updateInfo']);
    Route::post('/tenant/{tenantId}/update-theme',   [DashboardController::class, 'updateTheme']);
    Route::post('/tenant/{tenantId}/update-palette', [DashboardController::class, 'updatePalette']); // Legacy

    // Products CRUD
    Route::post('/tenant/{tenantId}/products',               [DashboardController::class, 'createProduct']);
    Route::put('/tenant/{tenantId}/products/{productId}',    [DashboardController::class, 'updateProduct']);
    Route::delete('/tenant/{tenantId}/products/{productId}', [DashboardController::class, 'deleteProduct']);

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
    Route::post('/tenant/{tenantId}/update-cta',                  [DashboardController::class, 'updateCta']);
    Route::post('/tenant/{tenantId}/update-business-hours',       [DashboardController::class, 'updateBusinessHours']);

    // Analytics (datos del tenant — privado)
    Route::get('/tenant/{tenantId}/analytics',                    [AnalyticsController::class, 'getData']);

    // QR download
    Route::get('/tenant/{tenantId}/qr/download',                  [QRTrackingController::class, 'downloadQR']);

}); // end middleware(['auth'])