<?php

namespace App\Providers;

use App\Models\FinancialYear;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        if (Schema::hasTable('financial_years')) {
            View::composer('*', function ($view) {
                $view->with('financialYears', FinancialYear::orderBy('start_date', 'desc')->get());
            });
        }
    }
}
