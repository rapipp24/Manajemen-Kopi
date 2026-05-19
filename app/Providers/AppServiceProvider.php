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
        view()->composer('components.layouts.admin', function ($view) {
            $count = \App\Models\RawMaterial::whereColumn('current_stock', '<=', 'minimum_stock')
                ->where('is_active', true)
                ->count();
            
            $pendingSalesCount = \App\Models\SalesOrder::where('status', 'menunggu')->count();
            $pendingDepositCount = \App\Models\SalesDeposit::where('status', 'menunggu_verifikasi')->count();
            
            $view->with([
                'lowStockCount' => $count,
                'pendingSalesOrderCount' => $pendingSalesCount,
                'pendingDepositCount' => $pendingDepositCount
            ]);
        });
    }
}
