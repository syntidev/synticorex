<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SyntiHelpController;

// Public endpoint (no auth required)
Route::get('/public/{subdomain}', [TenantController::class, 'showBySubdomain']);

// Read endpoints remain public.
Route::prefix('tenants')->group(function () {
    Route::get('/', [TenantController::class, 'index']);
    Route::get('/{id}', [TenantController::class, 'show']);

    // Nested routes: Products
    Route::prefix('{tenantId}/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    // Nested routes: Services
    Route::prefix('{tenantId}/services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::get('/{id}', [ServiceController::class, 'show']);
    });
});

// Mutation endpoints require API auth.
Route::middleware('auth:sanctum')->prefix('tenants')->group(function () {
    Route::post('/', [TenantController::class, 'store']);

    Route::middleware('tenant.owner:id')->group(function () {
        Route::put('/{id}', [TenantController::class, 'update']);
        Route::delete('/{id}', [TenantController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [TenantController::class, 'toggleStatus']);
    });

    Route::prefix('{tenantId}/products')->middleware('tenant.owner:tenantId')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::patch('/reorder', [ProductController::class, 'reorder']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::patch('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::patch('/{id}/toggle-active', [ProductController::class, 'toggleActive']);
        Route::patch('/{id}/toggle-featured', [ProductController::class, 'toggleFeatured']);
    });

    Route::prefix('{tenantId}/services')->middleware('tenant.owner:tenantId')->group(function () {
        Route::post('/', [ServiceController::class, 'store']);
        Route::patch('/reorder', [ServiceController::class, 'reorder']);
        Route::put('/{id}', [ServiceController::class, 'update']);
        Route::patch('/{id}', [ServiceController::class, 'update']);
        Route::delete('/{id}', [ServiceController::class, 'destroy']);
        Route::patch('/{id}/toggle-active', [ServiceController::class, 'toggleActive']);
    });
});

// SYNTiA pública — sin auth, solo docs marketing
Route::post('/synti/public-ask', [SyntiHelpController::class, 'publicAsk'])
    ->middleware('throttle:10,60');
