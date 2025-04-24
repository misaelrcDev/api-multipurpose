<?php

namespace ApiMultipurpose\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \ApiMultipurpose\Repositories\UserRepositoryInterface::class,
            \ApiMultipurpose\Repositories\UserRepository::class
        );
        $this->app->bind(
            \ApiMultipurpose\Services\UserServiceInterface::class,
            \ApiMultipurpose\Services\UserService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
