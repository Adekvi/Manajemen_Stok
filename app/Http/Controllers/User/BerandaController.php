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
        $produkAll = Data_produk::where('status', 'aktif')->count();
        $ttlMasuk = Data_stokmasuk::where('status', 'posted')->count();
        $ttlKeluar = Data_stokkeluar::where('status', 'posted')->count();

        $activityTransaksi = Data_stokkeluar::with(['produk', 'creator.dataDiri'])
            ->where('created_by', Auth::id()) // pastikan kolom ini ada
            ->latest()
            ->take(5)
            ->get();

        /* ==============================
            ATTENDANCE (STOK KELUAR USER)
        ============================== */
        $last7Days = collect(range(0, 6))
            ->map(fn($i) => Carbon::now()->subDays($i))
            ->reverse();

        $kartustok = Data_stokkeluar::select(
            DB::raw('DATE(tanggal_keluar) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_by', Auth::id())
            ->where('tanggal_keluar', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        /* LABEL */
        $attendanceLabels = $last7Days
            ->map(fn($date) => $date->translatedFormat('D')) // Sen, Sel
            ->values()
            ->toArray();

        /* DATA */
        $attendanceData = $last7Days
            ->map(fn($date) => (int) ($kartustok[$date->format('Y-m-d')] ?? 0))
            ->values()
            ->toArray();

        /* ==============================
            PERSENTASE (OPSIONAL)
        ============================== */
        $totalTransactions = array_sum($attendanceData);
        $averageDaily = $totalTransactions / 7;

        $targetPerDay = 50; // 🔥 sesuaikan bisnis
        $attendancePercentage = $targetPerDay > 0
            ? round(($averageDaily / $targetPerDay) * 100, 1)
            : 0;


        /* ==============================
            STATUS PRODUKSI (USER)
        ============================== */
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
            'draft' => $productionData[0],
            'posted' => $productionData[1],
            'cancelled' => $productionData[2],
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.dashboard.activity-table', compact('activityTransaksi'))->render()
            ]);
        }

        return view('dashboard', compact(
            'produkAll',
            'ttlMasuk',
            'ttlKeluar',
            'activityTransaksi',
            'attendanceLabels',
            'attendanceData',
            'attendancePercentage',
            'productionData',
            'productionStats'
        ));
    }
}
