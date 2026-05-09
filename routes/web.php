<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\RawMaterialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\User\ProductController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── AREA ADMIN (Khusus Admin) ───────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('units', UnitController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::get('/raw-materials/{raw_material}/movements', [RawMaterialController::class, 'movements'])->name('raw-materials.movements');
    Route::resource('raw-materials', RawMaterialController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Transaksi
    Route::resource('raw-material-receipts', \App\Http\Controllers\RawMaterialReceiptController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['raw-material-receipts' => 'raw_material_receipt:receipt_number']);
    Route::get('/production', function () {
        return "Halaman Produksi";
    })->name('production');
    Route::get('/packing', function () {
        return "Halaman Packing";
    })->name('packing');
    Route::get('/sales', function () {
        return "Halaman Penjualan";
    })->name('sales');
    Route::get('/orders', function () {
        return "Halaman Pesanan Admin";
    })->name('orders');
    Route::get('/reports', function () {
        return "Halaman Laporan";
    })->name('reports');
});

// ─── AREA USER / STAFF (Khusus Staff) ─────────────────────────────────────────
Route::middleware(['auth', 'staff', 'verified'])->name('user.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/orders', function () {
        return "Halaman Buat Pesanan Staff";
    })->name('orders');
    Route::get('/orders/history', function () {
        return "Halaman Riwayat Pesanan Staff";
    })->name('orders.history');
});

// ─── Halaman Sukses Verifikasi (Hanya untuk Tab Baru) ────────────────────────
Route::get('/verified-success', function () {
    return view('auth.verified-success');
})->name('verification.success');

// ─── Cek Status Verifikasi (untuk Auto-Refresh) ──────────────────────────────
Route::middleware('auth')->get('/check-verification', function () {
    return response()->json([
        'verified' => request()->user()->hasVerifiedEmail()
    ]);
});

// ─── Redirect setelah login berdasarkan role ──────────────────────────────────
Route::middleware('auth')->get('/home', function () {
    if (request()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.products');
})->name('home');

// ─── Google Auth ─────────────────────────────────────────────────────────────
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// Route auth dari Breeze
require __DIR__ . '/auth.php';
