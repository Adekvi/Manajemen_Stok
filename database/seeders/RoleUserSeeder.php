<?php

namespace Database\Seeders;

use App\Models\Admin\HakAkses\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        $allMenus = Menu::pluck('id')->toArray();

        // ✅ ADMIN = akses semua
        $adminRole->menus()->sync($allMenus);

        // ✅ USER = akses terbatas
        $userMenus = Menu::whereIn('route', [
            'user.dashboard',
            'user.produk',
            'user.stok.masuk',
            'user.stok.keluar',
            'user.kartu',
        ])->pluck('id')->toArray();

        $userRole->menus()->sync($userMenus);
    }
}
