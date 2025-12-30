<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Observers\SaleObserver;
use App\Observers\SaleItemObserver;

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
        // Registrar observers
        Sale::observe(SaleObserver::class);
        SaleItem::observe(SaleItemObserver::class);
    }
}