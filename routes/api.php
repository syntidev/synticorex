<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;

// Public endpoint (no auth required)
Route::get('/public/{subdomain}', [TenantController::class, 'showBySubdomain']);

// Protected routes (add auth:sanctum middleware in production)
Route::prefix('tenants')->group(function () {
    Route::get('/', [TenantController::class, 'index']);
    Route::post('/', [TenantController::class, 'store']);
    Route::get('/{id}', [TenantController::class, 'show']);
    Route::put('/{id}', [TenantController::class, 'update']);
    Route::delete('/{id}', [TenantController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [TenantController::class, 'toggleStatus']);
    
    // Nested routes: Products
    Route::prefix('{tenantId}/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::patch('/reorder', [ProductController::class, 'reorder']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::patch('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::patch('/{id}/toggle-active', [ProductController::class, 'toggleActive']);
        Route::patch('/{id}/toggle-featured', [ProductController::class, 'toggleFeatured']);
    });
    
    // Nested routes: Services
    Route::prefix('{tenantId}/services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::patch('/reorder', [ServiceController::class, 'reorder']);
        Route::get('/{id}', [ServiceController::class, 'show']);
        Route::put('/{id}', [ServiceController::class, 'update']);
        Route::patch('/{id}', [ServiceController::class, 'update']);
        Route::delete('/{id}', [ServiceController::class, 'destroy']);
        Route::patch('/{id}/toggle-active', [ServiceController::class, 'toggleActive']);
    });
});
