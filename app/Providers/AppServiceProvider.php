<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Peminjaman;
use App\Observers\PeminjamanObserver;

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
        // Register Peminjaman Observer
        Peminjaman::observe(PeminjamanObserver::class);
    }
}
