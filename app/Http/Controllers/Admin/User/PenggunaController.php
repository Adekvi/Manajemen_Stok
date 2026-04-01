<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['dataDiri' => function ($q) {
            $q->select('id', 'user_id', 'nama_lengkap', 'foto_diri');
        }])
            ->select('id', 'username', 'email', 'role', 'is_online', 'is_active')
            ->where('role', 'user')
            ->orderBy('id', 'desc');

        // === FITUR SEARCH ===
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                // Jika ingin mencari juga di nama_lengkap dari tabel dataDiri:
                // ->orWhereHas('dataDiri', function ($qd) use ($search) {
                //     $qd->where('nama_lengkap', 'like', "%{$search}%");
                // });
            });
        }

        $users = $query->paginate(10)->withQueryString();

        // Jika request AJAX (dari search)
        if ($request->ajax()) {
            $table = view('admin.user.table', compact('users'))->render();

            return response()->json([
                'html' => $table
            ]);
        }

        return view('admin.user.view', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'   => 'required|string|min:3|unique:users,username',
            'email'      => 'nullable|email|unique:users,email',
            'password'   => 'required|min:3',
            'role'       => 'required|in:user',
            'is_active'  => 'required|in:0,1',
        ]);

        $user = User::create([
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'is_active'  => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan',
            'user_id' => $user->id
        ]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'username'   => 'required|string|min:3|unique:users,username,' . $id,
            'email'      => 'nullable|email|unique:users,email,' . $id,
            'password'   => 'nullable|min:3',
            'role'       => 'required|in:user',
            'is_active'  => 'required|in:0,1',
        ]);

        $user = User::findOrFail($id);

        $updateData = [
            'username'  => $request->username,
            'email'     => $request->email,
            'role'      => $request->role,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
        ]);
    }

    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return response()->json($user);
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|in:0,1'
        ]);

        $user = User::where('role', 'user')->findOrFail($id);

        $oldStatus = $user->is_active;
        $newStatus = (bool) $request->is_active;

        $user->update([
            'is_active' => $newStatus
        ]);

        $message = $newStatus
            ? 'Pengguna berhasil diaktifkan kembali.'
            : 'Pengguna berhasil dinonaktifkan. User tersebut tidak dapat login lagi.';

        // Optional: Jika user sedang online dan kita nonaktifkan, bisa ditambahkan logic force logout nanti

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => $newStatus
        ]);
    }
}
