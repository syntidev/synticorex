<?php
// C:\laragon\www\synticorex\routes\web.php

use Illuminate\Support\Facades\Route;

Route::domain('app.synticorex.test')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); // Volveremos al dashboard original por ahora
        })->name('dashboard');
    });

    require __DIR__ . '/auth.php';
});