<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        // role người dùng
        Gate::define('user', function (User $usr) {
            return $usr->role === '01';
        });

        // role người bán hàng
        Gate::define('salesman', function (User $usr) {
            return $usr->role === '02';
        });

        // role admin
        Gate::define('admin', function (User $usr) {
            return $usr->role === '03';
        });
    }
}
