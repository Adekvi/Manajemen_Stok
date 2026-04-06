<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->is_active) {
            abort(403);
        }

        $routeName = $request->route()->getName();

        $allowedRoutes = cache()->remember(
            "permissions_user_{$user->id}",
            60,
            function () use ($user) {
                return $user->roles
                    ->load('menus')
                    ->pluck('menus')
                    ->flatten()
                    ->pluck('route')
                    ->filter()
                    ->unique()
                    ->toArray();
            }
        );

        if (!in_array($routeName, $allowedRoutes)) {
            abort(403);
        }

        return $next($request);
    }
}
