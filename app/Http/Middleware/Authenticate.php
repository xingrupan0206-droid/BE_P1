<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Waarheen redirecten als de gebruiker niet is ingelogd.
     */
    protected function redirectTo($request): ?string
    {
        // Als het een API-call is, geen redirect maar 401 teruggeven
        if ($request->expectsJson()) {
            return null;
        }

        // Anders: stuur naar de loginpagina
        return route('login');
    }
}