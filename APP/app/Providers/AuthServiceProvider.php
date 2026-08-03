<?php

namespace App\Providers;

use App\Auth\JwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        Auth::extend('sso', function ($app, $name, array $config) {
            return new JwtGuard();
        });


        Gate::before(function ($user, $ability) {

            foreach ($user->claims as $claim) {

                if (
                    strtolower(trim($claim['ClaimType'] ?? '')) === strtolower(trim($ability))
                    &&
                    filter_var($claim['ClaimValue'] ?? false, FILTER_VALIDATE_BOOLEAN)
                ) {
                    return true;
                }
            }

            return null;
        });
    }
}
