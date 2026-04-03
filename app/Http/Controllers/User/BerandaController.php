<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        $produkAll   = $this->getTotalProduk();
        $ttlMasuk    = $this->getTotalStokMasuk();
        $ttlKeluar   = $this->getTotalStokKeluar();
        $activityTransaksi = $this->getRecentActivities();

        // Data untuk Chart
        $attendanceLabels = [];
        $attendanceData   = [];
        $attendancePercentage = 0;
        $productionData   = [];
        $productionStats  = [];

        $this->prepareAttendanceChart($attendanceLabels, $attendanceData, $attendancePercentage);
        $this->prepareProductionChart($productionData, $productionStats);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.dashboard.activity-table', compact('activityTransaksi'))->render()
            ]);
        }

        $produkHariini = Data_produk::whereDate('created_at', Carbon::today())
            ->count();

        // dd($produkHariini);

        $stokMasukHariIni = Data_stokmasuk::where('status', 'posted')
            ->whereDate('tanggal_masuk', Carbon::today())
            ->count();

        $stokKeluarHariIni = Data_stokkeluar::where('created_by', Auth::id())
            ->where('status', 'posted')
            ->whereDate('tanggal_keluar', Carbon::today())
            ->count();

        $stokCancel = Data_stokkeluar::where('created_by', Auth::id())
            ->where('status', 'cancelled')
            ->count();

        // =========================
        // KEMARIN (UNTUK PERSENTASE)
        // =========================
        $produkKemarin = Data_produk::whereDate('created_at', Carbon::yesterday())
            ->count();

        // =========================
        // HITUNG PERSENTASE PRODUK
        // =========================
        $persenProduk = $produkKemarin > 0
            ? round((($produkHariini - $produkKemarin) / $produkKemarin) * 100, 1)
            : 0;

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

    private function getTotalStokMasuk()
    {
        return Data_stokmasuk::where('status', 'posted')
            // ->where('created_by', Auth::id())
            ->count();
    }

    private function getTotalStokKeluar()
    {
        return Data_stokkeluar::where('status', 'posted')
            ->where('created_by', Auth::id())
            ->count();
    }

    private function getRecentActivities()
    {
        return Data_stokkeluar::with(['produk', 'creator.dataDiri'])
            ->where('created_by', Auth::id())
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Persiapkan data untuk Attendance Trend Chart (Line Chart)
     */
    private function prepareAttendanceChart(&$attendanceLabels, &$attendanceData, &$attendancePercentage)
    {
        $last7Days = collect(range(0, 6))
            ->map(fn($i) => Carbon::now()->subDays($i))
            ->reverse();

        // Ambil data stok keluar per tanggal (user sendiri)
        $kartustok = Data_stokkeluar::select(
            DB::raw('DATE(tanggal_keluar) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_by', Auth::id())
            ->where('tanggal_keluar', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        // Labels (Sen, Sel, Rab, dst)
        $attendanceLabels = $last7Days
            ->map(fn($date) => $date->translatedFormat('D'))
            ->values()
            ->toArray();

        // Data jumlah transaksi per hari
        $attendanceData = $last7Days
            ->map(fn($date) => (int) ($kartustok[$date->format('Y-m-d')] ?? 0))
            ->values()
            ->toArray();

        // Hitung persentase attendance (opsional)
        $totalTransactions = array_sum($attendanceData);
        $averageDaily      = $totalTransactions / 7;
        $targetPerDay      = 50; // sesuaikan dengan target bisnis kamu

        $attendancePercentage = $targetPerDay > 0
            ? round(($averageDaily / $targetPerDay) * 100, 1)
            : 0;
    }

    /**
     * Persiapkan data untuk Status Stok Keluar (Doughnut Chart)
     */
    private function prepareProductionChart(&$productionData, &$productionStats)
    {
        $keluar = Data_stokkeluar::select('status', DB::raw('COUNT(*) as total'))
            ->where('created_by', Auth::id())
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
