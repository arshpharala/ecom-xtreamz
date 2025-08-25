<?php

namespace App\Providers;

use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::middleware(['web'])
            ->prefix('v2')
            ->as('v2.')
            ->group(base_path('routes/web-v2.php'));

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
