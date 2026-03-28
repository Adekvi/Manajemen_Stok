<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IdleLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        /** @var User $user */
        $user = Auth::user();

        $maxIdle = config('session.lifetime') * 60;

        $lastActivity = session('last_activity');

        if ($lastActivity) {

            $idleTime = time() - $lastActivity;

            if ($idleTime > $maxIdle) {

                $user->is_online = false;
                $user->last_logout = now();
                $user->save();

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('message', 'Session anda habis karena tidak ada aktivitas.');
            }
        }

        session(['last_activity' => time()]);

        return $next($request);
    }
}
