<?php

namespace App\Providers;

use App\Models\ShipmentTransaction;
use App\Observers\ShipmentTransactionObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191); 
        ShipmentTransaction::observe(ShipmentTransactionObserver::class);
        Paginator::useTailwind();
    }
}

