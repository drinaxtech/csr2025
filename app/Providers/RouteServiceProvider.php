<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Http\Middleware\Admin;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Called before routes are registered.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // API routes (prefix /api, uses api middleware group)
           

            // Web routes (all others, uses web middleware group)
            Route::middleware('web')
                 ->group(base_path('routes/web.php'));
            Route::aliasMiddleware('admin', Admin::class);
        });
    }
}
