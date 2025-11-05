<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\IncomeTransaction;
use App\Models\ExpenseTransaction;
use App\Models\EquityTransaction;
use App\Models\RawMaterialUsage;
use App\Observers\ProductObserver;
use App\Observers\IncomeTransactionObserver;
use App\Observers\ExpenseTransactionObserver;
use App\Observers\EquityTransactionObserver;
use App\Observers\RawMaterialUsageObserver;

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
        Product::observe(ProductObserver::class);
        IncomeTransaction::observe(IncomeTransactionObserver::class);
        ExpenseTransaction::observe(ExpenseTransactionObserver::class);
        EquityTransaction::observe(EquityTransactionObserver::class);
        \App\Models\StockAddition::observe(\App\Observers\StockAdditionObserver::class);
        RawMaterialUsage::observe(RawMaterialUsageObserver::class);
    }
}
