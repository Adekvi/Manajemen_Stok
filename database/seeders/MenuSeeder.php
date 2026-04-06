<?php

namespace Database\Seeders;

use App\Models\Admin\HakAkses\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [

            // MAIN ADMIN
            [
                'name' => 'Dashboard',
                'route' => 'admin.dashboard',
                'icon' => 'layout-dashboard',
                'group' => 'Menu Utama',
                'order' => 1
            ],
            [
                'name' => 'Produk',
                'route' => 'admin.master.produk',
                'icon' => 'package-plus',
                'group' => 'Menu Utama',
                'order' => 2
            ],
            [
                'name' => 'Stok Masuk',
                'route' => 'admin.master.menu.stokmasuk',
                'icon' => 'square-pen',
                'group' => 'Menu Utama',
                'order' => 3
            ],
            [
                'name' => 'Stok Keluar',
                'route' => 'admin.master.menu.stokkeluar',
                'icon' => 'square-arrow-right-exit',
                'group' => 'Menu Utama',
                'order' => 4
            ],
            [
                'name' => 'Kartu Stok',
                'route' => 'admin.master.menu.kartustok',
                'icon' => 'credit-card',
                'group' => 'Menu Utama',
                'order' => 5
            ],

            // LAPORAN
            [
                'name' => 'Report',
                'route' => 'admin.master.menu.report',
                'icon' => 'bar-chart-3',
                'group' => 'Laporan',
                'order' => 6
            ],

            // MANAGEMENT
            [
                'name' => 'Pengguna',
                'route' => 'admin.pengguna',
                'icon' => 'contact',
                'group' => 'Management',
                'order' => 7
            ],
            [
                'name' => 'Menu Akses',
                'route' => 'admin.menu',
                'icon' => 'square-menu',
                'group' => 'Management',
                'order' => 8
            ],

            // LAINNYA
            [
                'name' => 'Pengumuman',
                'route' => 'admin.master.menu.info',
                'icon' => 'bell',
                'group' => 'Lainnya',
                'order' => 9
            ],

            // USER
            [
                'name' => 'Dashboard',
                'route' => 'user.dashboard',
                'icon' => 'layout-dashboard',
                'group' => 'Menu Utama',
                'order' => 1
            ],
            [
                'name' => 'Produk',
                'route' => 'user.produk',
                'icon' => 'package',
                'group' => 'Menu Utama',
                'order' => 2
            ],
            [
                'name' => 'Stok Masuk',
                'route' => 'user.stok.masuk',
                'icon' => 'square-pen',
                'group' => 'Menu Utama',
                'order' => 3
            ],
            [
                'name' => 'Stok Keluar',
                'route' => 'user.stok.keluar',
                'icon' => 'square-arrow-right-exit',
                'group' => 'Menu Utama',
                'order' => 4
            ],
            [
                'name' => 'Kartu Stok',
                'route' => 'user.kartu',
                'icon' => 'credit-card',
                'group' => 'Menu Utama',
                'order' => 5
            ],
        ];

        foreach ($menus as $menu) {

            $prefix = explode('.', $menu['route'])[0] ?? 'admin';

            Menu::updateOrCreate(
                ['route' => $menu['route']],
                array_merge($menu, [
                    'prefix' => $prefix
                ])
            );
        }
    }
}
