<?php

namespace ApiMultipurpose\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

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
        $this->app->bind(
            \ApiMultipurpose\Repositories\ConversationRepositoryInterface::class,
            \ApiMultipurpose\Repositories\ConversationRepository::class
        );
        $this->app->bind(
            \ApiMultipurpose\Services\ConversationServiceInterface::class,
            \ApiMultipurpose\Services\ConversationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
