<?php

namespace App\Providers;

use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;
use App\Services\Http\Controllers\Api\V1\Auth\Implementation\AuthService;
use App\Services\Http\Controllers\Api\V1\Degree\DegreeServiceInterface;
use App\Services\Http\Controllers\Api\V1\Degree\Implementation\DegreeService;
use App\Services\Http\Controllers\Api\V1\Student\Implementation\StudentService;
use App\Services\Http\Controllers\Api\V1\Student\StudentServiceInterface;
use App\Services\Http\Controllers\Api\V1\Teacher\Implementation\TeacherService;
use App\Services\Http\Controllers\Api\V1\Teacher\TeacherServiceInterface;
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
        $this->app->bind(StudentServiceInterface::class, function () {
            return new StudentService();
        });
        $this->app->bind(TeacherServiceInterface::class, function () {
            return new TeacherService();
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
