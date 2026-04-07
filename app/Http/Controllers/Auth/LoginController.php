<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        /* =====================
            VALIDASI
        ===================== */

        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        // Normalisasi input
        $login = trim(strtolower($request->username));

        /* =====================
             RATE LIMIT (ANTI BRUTE FORCE)
        ===================== */

        $key = 'login:' . $login . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'username' => 'Terlalu banyak percobaan login. Coba lagi nanti.'
            ]);
        }

        /* =====================
            DETEKSI FIELD LOGIN
        ===================== */

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        /* =====================
            ATTEMPT LOGIN (AMAN)
        ===================== */

        $credentials = [
            $field => $login,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {

            RateLimiter::hit($key, 60); // block 60 detik

            return back()->withErrors([
                'username' => 'Login gagal. Periksa kembali kredensial.'
            ])->withInput();
        }

        // Login sukses → reset limiter
        RateLimiter::clear($key);

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        /* =====================
            VALIDASI USER
        ===================== */

        $user->ensureRole();

        if (!$this->canUserLogin($user)) {

            Auth::logout();

            return redirect()->route('login')
                ->with('account_disabled', true)
                ->with('disabled_message', 'Akun dinonaktifkan.');
        }

        /* =====================
            UPDATE STATUS
        ===================== */

        $user->update([
            'is_online' => true,
            'last_login' => now(),
        ]);

        /* =====================
            COOKIE REMEMBER USERNAME
        ===================== */

        if ($request->boolean('remember')) {
            Cookie::queue('remember_username', $login, 43200);
        } else {
            Cookie::queue(Cookie::forget('remember_username'));
        }

        /* =====================
            ROLE CHECK
        ===================== */

        $role = $user->getPrimaryRole();

        if (!$role || !Route::has($role . '.dashboard')) {

            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['role' => 'Akses tidak valid']);
        }

        return redirect()->route($role . '.dashboard');
    }

    protected function canUserLogin(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->is_active === true;
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            $user->is_online = false;
            $user->last_logout = now();
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
