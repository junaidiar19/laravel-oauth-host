<?php

namespace App\Providers;

use App\Models\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        // Hash the client secrets
        Passport::hashClientSecrets();

        // Set the expiration time for the tokens
        Passport::tokensExpireIn(now()->addMinute());
        Passport::refreshTokensExpireIn(now()->addMinutes(5));

        // Set the client model
        Passport::useClientModel(Client::class);

        // Define the scopes
        Passport::tokensCan([
            'view-user' => 'View user details',
        ]);
    }
}
