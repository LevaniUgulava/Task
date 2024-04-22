<?php

namespace App\Providers;

use App\Services\PayseraService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $accessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Replace with your actual access token

        $this->app->singleton(PayseraService::class, function ($app) use ($accessToken) {
            return new PayseraService($accessToken);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
