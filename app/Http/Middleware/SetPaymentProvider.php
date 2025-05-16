<?php

namespace App\Http\Middleware;

use Closure;

class SetPaymentProvider
{
    public function handle($request, Closure $next)
    {
        if ($request->has('provider')) {
            config(['payment.default' => $request->provider]);
        }

        return $next($request);
    }
}
