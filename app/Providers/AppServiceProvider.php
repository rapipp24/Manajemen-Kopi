<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share low stock count to all admin views
        view()->composer(['components.layouts.admin', 'layouts.admin'], function ($view) {
            $count = \App\Models\RawMaterial::whereColumn('current_stock', '<=', 'minimum_stock')
                ->where('is_active', true)
                ->count();
            
            $pendingSalesCount = \App\Models\SalesOrder::where('status', 'menunggu')->count();
            $pendingDepositCount = \App\Models\SalesDeposit::where('status', 'menunggu_verifikasi')->count();
            $pendingReturnCount = \App\Models\SalesReturn::where('status', 'menunggu')->count();
            
            $outOfStockProductCount = \App\Models\Product::where('current_stock', '<=', 0)
                ->where('is_active', true)
                ->count();

            // Badge merah Manajemen User: Sales yang sudah verifikasi email tapi belum disetujui
            $pendingApprovalCount = \App\Models\User::where('role', \App\Models\User::ROLE_SALES)
                ->whereNotNull('email_verified_at')
                ->where('approval_status', \App\Models\User::APPROVAL_PENDING)
                ->count();
            
            $view->with([
                'lowStockCount'          => $count,
                'pendingSalesOrderCount' => $pendingSalesCount,
                'pendingDepositCount'    => $pendingDepositCount,
                'pendingReturnCount'     => $pendingReturnCount,
                'outOfStockProductCount' => $outOfStockProductCount,
                'pendingApprovalCount'   => $pendingApprovalCount,
            ]);
        });
    }
}
