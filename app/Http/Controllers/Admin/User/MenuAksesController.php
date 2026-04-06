<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\HakAkses\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class MenuAksesController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles.menus')
            ->whereHas('roles', fn($q) => $q->where('name', 'user'))
            ->get();

        $menus = Menu::where('is_active', true)
            ->where('prefix', 'user')
            ->orderBy('group_order')
            ->orderBy('order')
            ->get()
            ->groupBy('group');

        $selectedUser = null;
        $userMenus = [];

        if ($request->user_id) {
            $selectedUser = $users->firstWhere('id', $request->user_id);

            $userMenus = $selectedUser->roles
                ->pluck('menus')
                ->flatten()
                ->pluck('id')
                ->toArray();
        }

        return view('admin.master.menu.akses.view', compact(
            'users',
            'menus',
            'selectedUser',
            'userMenus'
        ));
    }

    public function updateRoleMenus(Request $request, $userId)
    {
        $user = User::with('roles')->findOrFail($userId);

        $menus = $request->menus ?? [];

        foreach ($user->roles as $role) {
            $role->menus()->sync($menus);
        }

        // clear cache
        cache()->forget('sidebar_user_' . $userId);
        cache()->forget("permissions_user_{$userId}");

        return back()->with('success', 'Akses user berhasil diupdate');
    }
}
