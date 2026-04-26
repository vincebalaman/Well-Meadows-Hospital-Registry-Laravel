<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles) // ...$roles allows multiple roles
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Check if the user's role is in the list of allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
