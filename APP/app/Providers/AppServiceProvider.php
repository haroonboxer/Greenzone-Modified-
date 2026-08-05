<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\JwtGuard;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // // Add additional migration paths
        $this->loadMigrationsFrom([
            database_path('migrations'),
            database_path('migrations/auth_sys'),
            database_path('migrations/rms'),
            database_path('migrations/workshop'),
            database_path('migrations/green_zone'),
            //     // Add more paths as needed
        ]);
        Auth::extend('sso', function ($app, $name, array $config) {
           // logger()->info('Creating JwtGuard');
            return new JwtGuard();
        });
    }
}
