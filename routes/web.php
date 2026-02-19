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
});

// Tenant panel actions
Route::post('/tenant/{tenantId}/verify-pin',    [TenantRendererController::class, 'verifyPin']);
Route::post('/tenant/{tenantId}/toggle-status', [TenantRendererController::class, 'toggleStatus']);

// Tenant dashboard
Route::get('/tenant/{tenantId}/dashboard',      [DashboardController::class, 'index']);