<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = strtolower(Auth::user()->rolename ?? '');

                return match ($role) {
                    'admin' => redirect()->route('admin.index'),
                    'magazijnmedewerker' => redirect()->route('magazijnmedewerker.index'),
                    default => redirect()->route('klant.index'),
                };
            }
        }

        return $next($request);
    }
}