<?php

namespace App\Providers;

use App\Models\ShipmentTransaction;
use App\Models\Setting;
use App\Observers\ShipmentTransactionObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function ($user) {
            return $user->hasRole('super-admin') ? true : null;
        });

        Schema::defaultStringLength(191); 
        ShipmentTransaction::observe(ShipmentTransactionObserver::class);
        Paginator::useTailwind();

        // Share system settings globally (name + logo) and set app name for titles
        view()->composer('*', function ($view) {
            $setting = Setting::first();
            if ($setting) {
                config(['app.name' => $setting->system_name]);
            }
            $view->with('appSetting', $setting);
        });
    }
}
