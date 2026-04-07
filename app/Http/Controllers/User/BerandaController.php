<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_kartustok;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Admin\Master\Data_stokmasuk;
use App\Models\Data_produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // GLOBAL (sesuai requirement)
        $produkAll   = $this->getTotalProduk();
        $produkHariini = Data_produk::whereDate('created_at', Carbon::today())->count();

        // USER ONLY
        $ttlMasuk    = $this->getTotalStokMasuk($userId);
        $ttlKeluar   = $this->getTotalStokKeluar($userId);
        $activityTransaksi = $this->getRecentActivities($userId);

        // CHART
        $attendanceLabels = [];
        $attendanceData   = [];
        $attendancePercentage = 0;
        $productionData   = [];
        $productionStats  = [];

        $this->prepareAttendanceChart($attendanceLabels, $attendanceData, $attendancePercentage, $userId);
        $this->prepareProductionChart($productionData, $productionStats, $userId);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.dashboard.activity-table', compact('activityTransaksi'))->render()
            ]);
        }

        // =========================
        // HARI INI (USER)
        // =========================
        $stokMasukHariIni = Data_stokmasuk::where('created_by', $userId)
            ->where('status', 'posted')
            ->whereDate('tanggal_masuk', Carbon::today())
            ->count();

        $stokKeluarHariIni = Data_stokkeluar::where('created_by', $userId)
            ->where('status', 'posted')
            ->whereDate('tanggal_keluar', Carbon::today())
            ->count();

        $stokCancel = Data_stokkeluar::where('created_by', $userId)
            ->where('status', 'cancelled')
            ->count();

        // =========================
        // KEMARIN (GLOBAL, karena produk global)
        // =========================
        $produkKemarin = Data_produk::whereDate('created_at', Carbon::yesterday())->count();

        if ($produkKemarin == 0 && $produkHariini == 0) {
            $persenProduk = 0;
        } elseif ($produkKemarin == 0 && $produkHariini > 0) {
            $persenProduk = 100; // growth baru
        } elseif ($produkHariini == 0) {
            $persenProduk = 0; // 🔥 bukan -100%
        } else {
            $persenProduk = round((($produkHariini - $produkKemarin) / $produkKemarin) * 100, 1);
        }

        return view('dashboard', compact(
            'produkAll',
            'ttlMasuk',
            'ttlKeluar',
            'produkHariini',
            'stokMasukHariIni',
            'stokKeluarHariIni',
            'stokCancel',
            'activityTransaksi',
            'attendanceLabels',
            'attendanceData',
            'attendancePercentage',
            'productionData',
            'productionStats',
            'persenProduk'
        ));
    }

    // ========================================
    // PRIVATE METHODS
    // ========================================

    private function getTotalProduk()
    {
        return Data_produk::where('status', 'aktif')
            // ->where('created_by', Auth::id())
            ->count();
    }

    private function getTotalStokMasuk($userId)
    {
        return Data_stokmasuk::where('created_by', $userId)
            ->where('status', 'posted')
            ->count();
    }

    private function getTotalStokKeluar($userId)
    {
        return Data_stokkeluar::where('created_by', $userId)
            ->where('status', 'posted')
            ->count();
    }

    private function getRecentActivities($userId)
    {
        return Data_kartustok::with(['produk', 'user.dataDiri'])
            ->where('user_id', $userId)
            ->orderByDesc('tanggal')
            ->take(5)
            ->get();
    }

    /**
     * Persiapkan data untuk Attendance Trend Chart (Line Chart)
     */
    private function prepareAttendanceChart(&$attendanceLabels, &$attendanceData, &$attendancePercentage, $userId)
    {
        $last7Days = collect(range(0, 6))
            ->map(fn($i) => Carbon::now()->subDays($i))
            ->reverse();

        $kartustok = Data_stokkeluar::select(
            DB::raw('DATE(tanggal_keluar) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_by', $userId)
            ->where('tanggal_keluar', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $attendanceLabels = $last7Days
            ->map(fn($date) => $date->translatedFormat('D'))
            ->values()
            ->toArray();

        $attendanceData = $last7Days
            ->map(fn($date) => (int) ($kartustok[$date->format('Y-m-d')] ?? 0))
            ->values()
            ->toArray();

        $totalTransactions = array_sum($attendanceData);
        $averageDaily      = $totalTransactions / 7;
        $targetPerDay      = 50;

        $attendancePercentage = $targetPerDay > 0
            ? round(($averageDaily / $targetPerDay) * 100, 1)
            : 0;
    }

    /**
     * Persiapkan data untuk Status Stok Keluar (Doughnut Chart)
     */
    private function prepareProductionChart(&$productionData, &$productionStats, $userId)
    {
        $keluar = Data_stokkeluar::select('status', DB::raw('COUNT(*) as total'))
            ->where('created_by', $userId)
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['draft', 'posted', 'cancelled'];

        $productionData = collect($statuses)
            ->map(fn($status) => (int) ($keluar[$status] ?? 0))
            ->values()
            ->toArray();

        $productionStats = [
            'draft'     => $productionData[0] ?? 0,
            'posted'    => $productionData[1] ?? 0,
            'cancelled' => $productionData[2] ?? 0,
        ];
    }
}
