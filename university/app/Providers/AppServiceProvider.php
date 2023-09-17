<?php

namespace App\Providers;

use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;
use App\Services\Http\Controllers\Api\V1\Auth\Implementation\AuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, function () {
            return new AuthService();
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
