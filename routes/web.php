<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\RawMaterialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\PackingController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\DeliveryReportController as AdminDeliveryReportController;
use App\Http\Controllers\Admin\SalesReturnController as AdminSalesReturnController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductCategoryController;
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
    Route::resource('product-categories', ProductCategoryController::class);
    Route::get('/raw-materials/{raw_material}/movements', [RawMaterialController::class, 'movements'])->name('raw-materials.movements');
    Route::resource('raw-materials', RawMaterialController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Transaksi
    Route::resource('raw-material-receipts', \App\Http\Controllers\RawMaterialReceiptController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['raw-material-receipts' => 'raw_material_receipt:receipt_number']);
    Route::resource('productions', ProductionController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('packings', PackingController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
    Route::post('/sales/{sale}/payments', [SaleController::class, 'storePayment'])->name('sales.payments.store');
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/orders', function () {
        return "Halaman Pesanan Admin";
    })->name('orders');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::get('/basic-reports', [\App\Http\Controllers\Admin\BasicReportController::class, 'index'])->name('basic-reports.index');
    Route::get('/basic-reports/export-excel', [\App\Http\Controllers\Admin\BasicReportController::class, 'exportExcel'])->name('basic-reports.export-excel');
    Route::get('/basic-reports/export-pdf', [\App\Http\Controllers\Admin\BasicReportController::class, 'exportPdf'])->name('basic-reports.export-pdf');

    // Sales Orders (Pengajuan Barang dari Sales)
    Route::get('/sales-orders', [SalesOrderController::class, 'index'])->name('sales-orders.index');
    Route::get('/sales-orders/{salesOrder}', [SalesOrderController::class, 'show'])->name('sales-orders.show');
    Route::patch('/sales-orders/{salesOrder}/status', [SalesOrderController::class, 'updateStatus'])->name('sales-orders.update-status');

    // Laporan Pengiriman Sales (Admin: read-only)
    Route::get('/delivery-reports', [AdminDeliveryReportController::class, 'index'])->name('delivery-reports.index');
    Route::get('/delivery-reports/{deliveryReport}', [AdminDeliveryReportController::class, 'show'])->name('delivery-reports.show');
    Route::post('/delivery-reports/{deliveryReport}/resolve-overpayment', [AdminDeliveryReportController::class, 'resolveOverpayment'])->name('delivery-reports.resolve-overpayment');
    Route::get('/sales-deposits', [\App\Http\Controllers\Admin\SalesDepositController::class, 'index'])->name('sales-deposits.index');
    Route::get('/sales-deposits/{deposit}', [\App\Http\Controllers\Admin\SalesDepositController::class, 'show'])->name('sales-deposits.show');
    Route::post('/sales-deposits/{deposit}/approve', [\App\Http\Controllers\Admin\SalesDepositController::class, 'approve'])->name('sales-deposits.approve');
    Route::post('/sales-deposits/{deposit}/reject', [\App\Http\Controllers\Admin\SalesDepositController::class, 'reject'])->name('sales-deposits.reject');

    // Return Sales (Verifikasi Return dari Sales)
    Route::get('/returns', [AdminSalesReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [AdminSalesReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/{return}/receive', [AdminSalesReturnController::class, 'receive'])->name('returns.receive');
    Route::post('/returns/{return}/reject', [AdminSalesReturnController::class, 'reject'])->name('returns.reject');

    // Pengaturan
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Karyawan Gudang & Absensi Manual
    Route::resource('warehouse-employees', \App\Http\Controllers\Admin\WarehouseEmployeeController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('attendances/mark', [\App\Http\Controllers\Admin\AttendanceController::class, 'mark'])
        ->name('attendances.mark');
    Route::resource('attendances', \App\Http\Controllers\Admin\AttendanceController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

// ─── AREA SALES (Khusus Sales) ────────────────────────────────────────────────
Route::middleware(['auth', 'sales', 'verified'])->name('sales.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');

    // Pengajuan Barang Sales
    Route::resource('orders', \App\Http\Controllers\Sales\OrderController::class)->only(['index', 'create', 'store', 'show']);

    // Laporan Pengiriman ke Toko
    Route::resource('delivery-reports', \App\Http\Controllers\Sales\DeliveryReportController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Setoran Sales Lapangan
    Route::resource('deposits', \App\Http\Controllers\Sales\SalesDepositController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Return Barang dari Toko
    Route::resource('returns', \App\Http\Controllers\Sales\SalesReturnController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::get('/orders-placeholder', function () {
        return "Halaman Buat Pesanan Sales";
    })->name('orders-placeholder');
    Route::get('/orders/history', function () {
        return "Halaman Riwayat Pesanan Sales";
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
    return redirect()->route('sales.products');
})->name('home');

// ─── Google Auth ─────────────────────────────────────────────────────────────
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// Route auth dari Breeze
require __DIR__ . '/auth.php';
