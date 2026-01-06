<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Observers\SaleObserver;
use App\Observers\SaleItemObserver;
use App\Models\StockEntry;
use App\Models\StockEntryItem;
use App\Observers\StockEntryObserver;
use App\Observers\StockEntryItemObserver;

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
        Sale::observe(SaleObserver::class);
        SaleItem::observe(SaleItemObserver::class);
        StockEntry::observe(StockEntryObserver::class);
        StockEntryItem::observe(StockEntryItemObserver::class);
    }
}