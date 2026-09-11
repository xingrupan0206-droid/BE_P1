<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowedRoles = [];

        foreach ($roles as $role) {
            foreach (explode(',', $role) as $rolePart) {
                $allowedRoles[] = strtolower(trim($rolePart));
            }
        }

        $userRole = strtolower($user->rolename ?? '');

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Onvoldoende rechten');
        }

        return $next($request);
    }

}
