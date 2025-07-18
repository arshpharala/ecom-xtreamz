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
        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
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
