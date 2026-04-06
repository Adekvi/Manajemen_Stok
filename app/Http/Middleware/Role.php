<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        $roles = explode(',', $roles);

        $hasRole = $user->roles()
            ->whereIn('name', $roles)
            ->exists();

        if (!$hasRole) {
            return redirect('/')
                ->with('error', 'Akses dilarang.');
        }

        return $next($request);
    }
}
