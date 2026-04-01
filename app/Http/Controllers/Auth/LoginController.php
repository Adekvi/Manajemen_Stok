<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

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

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user) {
            return back()->withErrors(['username' => 'User tidak ditemukan.'])
                ->withInput();   // penting: agar old() bisa ambil input
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.'])
                ->withInput();
        }

        if (!$this->canUserLogin($user)) {
            return redirect()->route('login')
                ->with('account_disabled', true)
                ->with('disabled_message', 'Akun Anda telah dinonaktifkan oleh Admin.')
                ->withInput();
        }

        // Hancurkan session lama
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Update status user
        $user->update([
            'is_online' => true,
            'last_login' => now(),
        ]);

        // === BAGIAN BARU: Simpan username & password ke Cookie jika Remember Me dicentang ===
        if ($request->boolean('remember')) {
            // Simpan selama 30 hari (bisa kamu ubah)
            Cookie::queue('username', $request->username, 43200);     // 30 hari * 24 jam * 60 menit
            Cookie::queue('password', $request->password, 43200);     // plaintext password (lihat catatan keamanan di bawah)
        } else {
            // Jika tidak centang Remember Me, hapus cookie agar tidak muncul lagi
            Cookie::queue(Cookie::forget('username'));
            Cookie::queue(Cookie::forget('password'));
        }

        return redirect()->route($user->role . '.dashboard');
    }

    protected function canUserLogin(User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Hanya role user yang dibatasi
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
