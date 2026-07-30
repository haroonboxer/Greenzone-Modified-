<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
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
