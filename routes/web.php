<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Master\Menu\KartuController;
use App\Http\Controllers\Admin\Master\Menu\KeluarController;
use App\Http\Controllers\Admin\Master\Menu\ProdukController;
use App\Http\Controllers\Admin\Master\Menu\InfoController;
use App\Http\Controllers\Admin\Master\Menu\MasukController;
use App\Http\Controllers\Admin\Master\Produk\BarangKeluarController;
use App\Http\Controllers\Admin\Master\Produk\BarangMasukController;
use App\Http\Controllers\Admin\Master\Produk\DataProdukController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\User\MenuAksesController;
use App\Http\Controllers\Admin\User\PenggunaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\User\BerandaController;
use App\Http\Controllers\User\NotifikasiController;
use App\Http\Controllers\User\Produk\PackageController;
use App\Http\Controllers\User\Stok\BrgKeluarController;
use App\Http\Controllers\User\Stok\BrgMasukController;
use App\Http\Controllers\User\Stok\KartuStokController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'idle', 'active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/store', [ProfileController::class, 'update'])->name('profile.store');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login-store', [LoginController::class, 'store'])->name('auth.login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('auth.logout');
});

Route::middleware(['auth', 'idle', 'active'])->prefix('menu')->group(function () {
    Route::get('/setting', [SettingController::class, 'index'])->name('menu.setting');
    Route::post('/setting-update', [SettingController::class, 'update'])->name('menu.update');

    Route::get('/search/global', [SearchController::class, 'global'])->name('search.global');
});

// Admin
Route::middleware(['auth', 'idle', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStatsAjax'])->name('admin.stats');

    Route::prefix('/menu')->group(function () {
        Route::get('/view', [MenuAksesController::class, 'index'])->name('admin.menu');
        Route::put('/update/{id}', [MenuAksesController::class, 'updateRoleMenus'])
            ->name('admin.menu.update');
    });

    Route::prefix('master/menu')->group(function () {

        // Produk
        Route::get('/produk', [ProdukController::class, 'index'])
            ->name('admin.master.produk');

        Route::get('/produk/generate-kode', [ProdukController::class, 'generateKode'])
            ->name('admin.produk.generate-kode');

        Route::post('/produk-tambah', [DataProdukController::class, 'store'])
            ->name('admin.produk.tambah');

        Route::get('/produk-view/{id}', [ProdukController::class, 'show'])
            ->name('admin.produk.detail');

        Route::put('/produk-edit/{id}', [DataProdukController::class, 'update'])
            ->name('admin.produk.edit');

        Route::delete('/produk-hapus/{id}', [DataProdukController::class, 'destroy'])
            ->name('admin.produk.hapus');


        // Stok Masuk
        Route::get('/stokmasuk', [MasukController::class, 'index'])
            ->name('admin.master.menu.stokmasuk');

        Route::post('/stok-transaksi', [BarangMasukController::class, 'store'])
            ->name('admin.stok-transaksi');

        Route::get('/stok-masuk/{id}', [MasukController::class, 'show'])
            ->name('admin.master.stokmasuk.detail');

        Route::put('/stok-edit/{id}', [BarangMasukController::class, 'update'])
            ->name('admin.master.stok.edit');

        Route::put('/stok-status/{id}', [MasukController::class, 'updateStatus']);

        Route::delete('/stok-hapus/{id}', [BarangMasukController::class, 'destroy'])
            ->name('admin.master.stok.hapus');


        // Stok Keluar
        Route::get('/stokkeluar', [KeluarController::class, 'index'])
            ->name('admin.master.menu.stokkeluar');

        Route::post('/stok-transaksi/keluar', [BarangKeluarController::class, 'store'])
            ->name('admin.stok-transaksi.keluar');

        Route::get('/stok-keluar/{id}', [KeluarController::class, 'show'])
            ->name('admin.master.stokkeluar.detail');

        Route::put('/stok-edit/keluar/{id}', [BarangKeluarController::class, 'update'])
            ->name('admin.master.keluar.edit');

        Route::put('/stok-status/keluar/{id}', [KeluarController::class, 'updateStatus']);

        Route::delete('/stok-hapus/keluar/{id}', [BarangKeluarController::class, 'destroy'])
            ->name('admin.master.keluar.hapus');


        // Kartu Stok
        Route::get('/kartustok', [KartuController::class, 'index'])
            ->name('admin.master.menu.kartustok');

        Route::get('/kartu-detail/{id}', [KartuController::class, 'show'])
            ->name('admin.master.kartu.detail');

        // Report
        Route::get('/report', [ReportController::class, 'index'])
            ->name('admin.master.menu.report');
        // EXPORT BULANAN
        Route::get('/report/export-bulanan', [ReportController::class, 'exportBulanan'])
            ->name('admin.report.exportBulanan');
        // EXPORT STOK
        Route::get('/report/export', [ReportController::class, 'export'])
            ->name('admin.report.export');

        // Pengumuman
        Route::get('/info', [InfoController::class, 'index'])
            ->name('admin.master.menu.info');

        Route::post('/info-tambah', [InfoController::class, 'store'])
            ->name('admin.info.store');

        Route::get('/info-show/{id}', [InfoController::class, 'show'])
            ->name('admin.info.show');

        Route::put('/info-edit/{id}', [InfoController::class, 'update'])
            ->name('admin.info.edit');

        Route::delete('/info-hapus/{id}', [InfoController::class, 'destroy'])
            ->name('admin.info-hapus');
    });

    Route::prefix('/pengguna')->group(function () {
        Route::get('/view', [PenggunaController::class, 'index'])
            ->name('admin.pengguna');
        Route::post('/tambah', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::put('/edit/{id}', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::get('/show/{id}', [PenggunaController::class, 'show'])->name('pengguna.show');
        Route::put('/status/{id}', [PenggunaController::class, 'status'])->name('pengguna.status');
    });
});

// User
Route::middleware(['auth', 'idle', 'active', 'role:user'])->prefix('user')->group(function () {

    Route::get('/dashboard', [BerandaController::class, 'index'])
        ->name('user.dashboard');

    // Produk
    Route::get('/produk', [PackageController::class, 'index'])
        ->name('user.produk');

    Route::get('/produk-view/{id}', [PackageController::class, 'show'])
        ->name('user.produk.detail');

    Route::prefix('/menu')->group(function () {
        // Stok Masuk
        Route::get('/stok-masuk', [BrgMasukController::class, 'index'])->name('user.stok.masuk');
        Route::post('/stok-transaksi/masuk', [BrgMasukController::class, 'store'])
            ->name('user.stok-transaksi.masuk');

        Route::get('/stok-masuk/{id}', [BrgMasukController::class, 'show'])
            ->name('user.stokmasuk.detail');

        Route::put('/stok-edit/masuk/{id}', [BrgMasukController::class, 'update'])
            ->name('user.masuk.edit');

        Route::put('/stok-status/masuk/{id}', [BrgMasukController::class, 'updateStatus'])->name('user.status.masuk');
        Route::delete('/stok-hapus/{id}', [BrgMasukController::class, 'destroy'])
            ->name('user.masuk.stok.hapus');

        // Stok Keluar
        Route::get('/stok-keluar', [BrgKeluarController::class, 'index'])->name('user.stok.keluar');
        Route::post('/stok-transaksi/keluar', [BrgKeluarController::class, 'store'])
            ->name('user.stok-transaksi.keluar');

        Route::get('/stok-keluar/{id}', [BrgKeluarController::class, 'show'])
            ->name('user.stokkeluar.detail');

        Route::put('/stok-edit/keluar/{id}', [BrgKeluarController::class, 'update'])
            ->name('user.keluar.edit');

        Route::put('/stok-status/keluar/{id}', [BrgKeluarController::class, 'updateStatus'])->name('user.status.keluar');

        // Kartu Stok
        Route::get('/kartustok', [KartuStokController::class, 'index'])
            ->name('user.kartu');

        Route::get('/kartu-detail/{id}', [KartuStokController::class, 'show'])
            ->name('user.kartu.detail');
    });

    Route::prefix('/info')->group(function () {
        // All notifikasi
        Route::get('/notifikasi/all', [NotifikasiController::class, 'all']);
        // Notifikasi terbaru
        Route::get('/notifikasi', [NotifikasiController::class, 'index']);
        Route::post('/notifikasi/read/{id}', [NotifikasiController::class, 'read']);
        Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll']);
    });
});
