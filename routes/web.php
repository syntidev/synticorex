<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
| Landings: middleware tenant resuelve el tenant por host (central_domains o dominio).
| Solo se alcanza el controlador si hay un tenant activo; si no, el middleware hace abort(404).
*/
Route::middleware('tenant')->group(function (): void {
    Route::get('/', [LandingController::class, 'show'])->name('landing.show');
});

Route::get('/welcome', function () {
    return view('welcome');
});
