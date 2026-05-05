<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\User\ProductController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Route Admin ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Master Data
    Route::resource('units', UnitController::class);
    Route::resource('suppliers', SupplierController::class);
});

// ─── Route User / Customer ────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
});

// ─── Redirect setelah login berdasarkan role ──────────────────────────────────
Route::middleware('auth')->get('/home', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.products');
})->name('home');

// Route auth dari Breeze (login, register, logout, dll.)
require __DIR__ . '/auth.php';
