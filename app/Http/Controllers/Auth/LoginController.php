<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::with('roles')
            ->where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'User tidak ditemukan.'
            ])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.'
            ])->withInput();
        }

        // 🚨 Pastikan user punya role
        $user->ensureRole();

        if (!$this->canUserLogin($user)) {
            return redirect()->route('login')
                ->with('account_disabled', true)
                ->with('disabled_message', 'Akun Anda telah dinonaktifkan oleh Admin.')
                ->withInput();
        }

        // Reset session lama
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->update([
            'is_online' => true,
            'last_login' => now(),
        ]);

        // Remember username
        if ($request->boolean('remember')) {
            Cookie::queue('remember_username', $request->username, 43200);
        } else {
            Cookie::queue(Cookie::forget('remember_username'));
        }

        // 🎯 Ambil role
        $role = $user->getPrimaryRole();

        if (!$role) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['role' => 'User tidak memiliki role']);
        }

        // 🚀 Redirect dinamis
        if (!Route::has($role . '.dashboard')) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['role' => 'Route tidak ditemukan untuk role: ' . $role]);
        }

        // dd($user->roles->pluck('name'), $user->getPrimaryRole());

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
