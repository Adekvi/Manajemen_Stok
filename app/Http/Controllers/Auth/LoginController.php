<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    // Buat fungsi login
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'User tidak ditemukan.'
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Hancurkan session lama jika masih ada
    |--------------------------------------------------------------------------
    */
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        /*
    |--------------------------------------------------------------------------
    | Login ulang
    |--------------------------------------------------------------------------
    */

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        /*
    |--------------------------------------------------------------------------
    | Update status user
    |--------------------------------------------------------------------------
    */

        $user->is_online = true;
        $user->last_login = now();
        $user->save();

        return redirect()->route($user->role . '.dashboard');
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
