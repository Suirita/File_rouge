<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Core';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapAuthRoutes();
        $this->mapSettingsRoutes();
        $this->mapConsoleRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/web.php'));
    }

    /**
     * Define the "auth" routes for the application.
     */
    protected function mapAuthRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/auth.php'));
    }

    /**
     * Define the "settings" routes for the application.
     */
    protected function mapSettingsRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/settings.php'));
    }

    /**
     * Define the "settings" routes for the application.
     */
    protected function mapConsoleRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/console.php'));
    }
}
