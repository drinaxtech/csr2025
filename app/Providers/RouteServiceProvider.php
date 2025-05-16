<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Get the post-authentication redirect path.
     *
     * @return string
     */
    public static function getRedirectRoute()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return '/admin/dashboard'; // Redirect admin users
            } else {
                return '/dashboard'; // Or wherever you want other users to go
            }
        }
        return '/login'; // Default if not authenticated.
    }
}
