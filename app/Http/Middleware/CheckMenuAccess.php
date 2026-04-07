<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->is_active) {
            abort(403);
        }

        $routeName = $request->route()->getName();

        // 🔥 gunakan version untuk invalidasi otomatis
        $cacheKey = "permissions_user_{$user->id}_v{$user->updated_at->timestamp}";

        $allowedRoutes = cache()->remember($cacheKey, 300, function () use ($user) {

            return $user->roles()
                ->with('menus')
                ->get()
                ->pluck('menus')
                ->flatten()
                ->pluck('route')
                ->filter()
                ->unique()
                ->toArray();
        });

        // 🔒 fallback safety (jaga-jaga cache error)
        if (!$allowedRoutes || !is_array($allowedRoutes)) {
            abort(403);
        }

        if (!in_array($routeName, $allowedRoutes)) {
            abort(403);
        }

        return $next($request);
    }
}
