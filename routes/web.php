<?php
// C:\laragon\www\synticorex\routes\web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenantRendererController;
use App\Http\Controllers\ImageUploadController;

Route::domain('app.synticorex.test')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); // Volveremos al dashboard original por ahora
        })->name('dashboard');
    });

    require __DIR__ . '/auth.php';
});

// Landing page pública por subdomain
Route::get('/{subdomain}', [TenantRendererController::class, 'show'])
    ->where('subdomain', '[a-z0-9-]+')
    ->name('tenant.landing');

// Image upload routes
Route::prefix('tenant/{tenantId}/upload')->group(function () {
    Route::post('/logo',                    [ImageUploadController::class, 'uploadLogo']);
    Route::post('/hero',                    [ImageUploadController::class, 'uploadHero']);
    Route::post('/product/{productId}',     [ImageUploadController::class, 'uploadProduct']);
    Route::post('/service/{serviceId}',     [ImageUploadController::class, 'uploadService']);
    // Gallery images (Plan 3 / VISIÓN only)
    Route::post('/product/{productId}/gallery',              [ImageUploadController::class, 'uploadProductGallery']);
    Route::delete('/product/{productId}/gallery/{imageId}',  [ImageUploadController::class, 'deleteProductGalleryImage']);
});

// Tenant panel actions
Route::post('/tenant/{tenantId}/verify-pin',    [TenantRendererController::class, 'verifyPin']);
Route::post('/tenant/{tenantId}/toggle-status', [TenantRendererController::class, 'toggleStatus']);

// Tenant dashboard
Route::get('/tenant/{tenantId}/dashboard',      [DashboardController::class, 'index']);
Route::post('/tenant/{tenantId}/update-info',   [DashboardController::class, 'updateInfo']);
Route::post('/tenant/{tenantId}/update-theme',  [DashboardController::class, 'updateTheme']);
Route::post('/tenant/{tenantId}/update-palette', [DashboardController::class, 'updatePalette']); // Legacy

// Products CRUD
Route::post('/tenant/{tenantId}/products',          [DashboardController::class, 'createProduct']);
Route::put('/tenant/{tenantId}/products/{productId}', [DashboardController::class, 'updateProduct']);
Route::delete('/tenant/{tenantId}/products/{productId}', [DashboardController::class, 'deleteProduct']);

// Services CRUD
Route::post('/tenant/{tenantId}/services',          [DashboardController::class, 'createService']);
Route::put('/tenant/{tenantId}/services/{serviceId}', [DashboardController::class, 'updateService']);
Route::delete('/tenant/{tenantId}/services/{serviceId}', [DashboardController::class, 'deleteService']);

// Branches (Plan 3 / VISIÓN)
Route::post('/tenant/{tenantId}/branches/toggle',          [DashboardController::class, 'toggleBranches']);
Route::post('/tenant/{tenantId}/branches',                 [DashboardController::class, 'saveBranch']);
Route::delete('/tenant/{tenantId}/branches/{branchId}',    [DashboardController::class, 'deleteBranch']);

// Payment Methods
Route::post('/tenant/{tenantId}/update-payment-methods',   [DashboardController::class, 'updatePaymentMethods']);

// Social Networks
Route::post('/tenant/{tenantId}/update-social-networks',   [DashboardController::class, 'updateSocialNetworks']);

// Section Order (drag & drop)
Route::post('/tenant/{tenantId}/dashboard/save-section-order', [DashboardController::class, 'saveSectionOrder'])
    ->name('tenant.dashboard.save-section-order');

// Custom palette (Plan 3)
Route::post('/tenant/{tenantId}/dashboard/save-custom-palette', [DashboardController::class, 'saveCustomPalette'])
    ->name('tenant.dashboard.save-custom-palette');

Route::post('/tenant/{tenantId}/dashboard/toggle-section', [DashboardController::class, 'toggleSection'])
    ->name('tenant.dashboard.toggle-section');

// Config actions
Route::post('/tenant/{tenantId}/update-currency-config', [DashboardController::class, 'updateCurrencyConfig']);
Route::post('/tenant/{tenantId}/update-pin',             [DashboardController::class, 'updatePin']);