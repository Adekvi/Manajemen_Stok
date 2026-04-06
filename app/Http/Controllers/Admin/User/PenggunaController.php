<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with([
            'roles',
            'dataDiri' => function ($q) {
                $q->select('id', 'user_id', 'nama_lengkap', 'foto_diri');
            }
        ])
            ->select('id', 'username', 'email', 'is_online', 'is_active')
            ->orderBy('id', 'desc');

        // === FITUR SEARCH ===
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            $table = view('admin.user.table', compact('users'))->render();

            return response()->json(['html' => $table]);
        }

        return view('admin.user.view', compact('users'));
    }

    // app/Http/Controllers/Admin/PenggunaController.php

    public function store(Request $request)
    {
        $request->validate([
            'username'   => 'required|string|min:3|unique:users,username',
            'email'      => 'nullable|email|unique:users,email',
            'password'   => 'required|min:3',
            'is_active'  => 'required|in:0,1',
        ]);

        $user = User::create([
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'is_active'  => $request->is_active,
        ]);

        // Attach role 'user' (Staff) — karena admin hanya boleh tambah role user
        $roleUser = Role::where('name', 'user')->first();
        if ($roleUser) {
            $user->roles()->attach($roleUser->id);
        }

        // Optional: Jalankan ensureRole jika ada method itu
        // $user->ensureRole();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan',
            'user_id' => $user->id
        ]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'username'  => 'required|string|min:3|unique:users,username,' . $id,
            'email'     => 'nullable|email|unique:users,email,' . $id,
            'password'  => 'nullable|min:3',
            'is_active' => 'required|in:0,1',
        ]);

        $user = User::findOrFail($id);

        $updateData = [
            'username'  => $request->username,
            'email'     => $request->email,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        // Role tetap 'user' (tidak boleh diubah menjadi admin oleh admin biasa)
        // Jika ingin fleksibel nanti, baru tambahkan logic sync role

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
        ]);
    }

    public function show($id)
    {
        $user = User::with('roles')
            ->select('id', 'username', 'email', 'is_active')
            ->findOrFail($id);

        return response()->json($user);
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|in:0,1'
        ]);

        $user = User::findOrFail($id);

        $newStatus = (bool) $request->is_active;

        $user->update(['is_active' => $newStatus]);

        $message = $newStatus
            ? 'Pengguna berhasil diaktifkan kembali.'
            : 'Pengguna berhasil dinonaktifkan. User tersebut tidak dapat login lagi.';

        // Clear cache sidebar user tersebut
        cache()->forget('sidebar_user_' . $user->id);

        return response()->json([
            'success'   => true,
            'message'   => $message,
            'is_active' => $newStatus
        ]);
    }
}
