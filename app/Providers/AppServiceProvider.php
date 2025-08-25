<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Contracts\Factory;
use App\Http\Middleware\AdminAuthenticate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new SocialiteManager($app);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::middleware(['web'])
            ->group(base_path('routes/auth.php'));

        Route::middleware(['api'])
            ->prefix('api')
            ->as('api.')
            ->group(base_path('routes/api.php'));

        Route::middleware(['web'])
            ->prefix('admin')
            ->as('admin.')
            ->group(base_path('routes/admin-auth.php'));

        Route::middleware(['web', AdminAuthenticate::class])
            ->prefix('admin')
            ->as('admin.')
            ->group(base_path('routes/admin.php'));
    }
}
