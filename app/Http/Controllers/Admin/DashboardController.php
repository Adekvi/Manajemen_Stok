<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_kartustok;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Admin\Master\Data_stokmasuk;
use Illuminate\Http\Request;
use App\Models\Data_produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $produkAll = Data_produk::where('status', 'aktif')->count();
        $ttlMasuk = Data_stokmasuk::where('status', 'posted')->count();
        $ttlKeluar = Data_stokkeluar::where('status', 'posted')->count();

        $recentTransaksi = Data_kartustok::with(['produk', 'user.dataDiri'])
            ->latest()
            ->take(5)
            ->get();

        /* ==============================
            ATTENDANCE (7 HARI)
        ============================== */
        $last7Days = collect(range(0, 6))->map(fn($i) => Carbon::now()->subDays($i))->reverse();

        $kartustok = Data_kartustok::select(
            DB::raw('DATE(tanggal) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('tanggal', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $attendanceLabels = $last7Days
            ->map(fn($date) => $date->translatedFormat('l'))
            ->values()
            ->toArray();

        // Data harian
        $attendanceData = $last7Days
            ->map(fn($date) => (int) ($kartustok[$date->format('Y-m-d')] ?? 0))
            ->values()
            ->toArray();

        // Hitung TOTAL transaksi 7 hari
        $totalTransactions = array_sum($attendanceData);

        // Hitung rata-rata harian (untuk persen tampilan utama)
        $averageDaily = $totalTransactions / 7;

        // Asumsi target maksimal per hari (contoh: 100 transaksi/hari = 100%)
        // GANTI SESUAI KEBUTUHAN BISNIS KAMU
        $targetPerDay = 100; // ← ubah ini sesuai target real (misal 80, 120, dll)

        $attendancePercentage = $targetPerDay > 0
            ? round(($averageDaily / $targetPerDay) * 100, 1)
            : 0;

        /* ==============================
            STATUS PRODUKSI
        ============================== */
        $masuk = Data_stokmasuk::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $keluar = Data_stokkeluar::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['draft', 'posted', 'cancelled'];

        $productionData = collect($statuses)->map(function ($status) use ($masuk, $keluar) {
            return ($masuk[$status] ?? 0) + ($keluar[$status] ?? 0);
        });

        $productionStats = [
            'draft' => $productionData[0],
            'posted' => $productionData[1],
            'cancelled' => $productionData[2],
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.dashboard.activity-table', compact('recentTransaksi'))->render()
            ]);
        }

        return view('admin.dashboard.view', compact(
            'produkAll',
            'ttlMasuk',
            'ttlKeluar',
            'recentTransaksi',
            'attendanceLabels',
            'attendanceData',
            'attendancePercentage',
            'productionData',
            'productionStats'
        ));
    }
}
