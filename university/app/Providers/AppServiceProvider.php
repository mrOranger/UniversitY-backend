<?php

namespace App\Providers;

use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;
use App\Services\Http\Controllers\Api\V1\Auth\Implementation\AuthService;
use App\Services\Http\Controllers\Api\V1\Degree\DegreeServiceInterface;
use App\Services\Http\Controllers\Api\V1\Degree\Implementation\DegreeService;
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
        $this->app->bind(DegreeServiceInterface::class, function () {
            return new DegreeService();
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
