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
        if ($request->ajax()) {
            return $this->getActivityAjax();
        }

        return view('admin.dashboard.view', array_merge(
            $this->getStats(),
            $this->getAttendance(),
            $this->getProduction(),
            $this->getRecentTransaksi()
        ));
    }

    private function getStats(): array
    {
        return [
            'produkAll' => Data_produk::where('status', 'aktif')->count(),
            'ttlMasuk' => Data_stokmasuk::where('status', 'posted')->count(),
            'ttlKeluar' => Data_stokkeluar::where('status', 'posted')->count(),
        ];
    }

    private function getRecentTransaksi(): array
    {
        return [
            'recentTransaksi' => Data_kartustok::with(['produk', 'user.dataDiri'])
                ->latest()
                ->limit(5)
                ->get()
        ];
    }

    private function getAttendance(): array
    {
        $dates = collect(range(0, 6))
            ->map(fn($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->reverse()
            ->values();

        $raw = Data_kartustok::select(
            DB::raw('DATE(tanggal) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('tanggal', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal')
            ->toArray(); // 🔥 penting

        $attendanceLabels = [];
        $attendanceData = [];

        foreach ($dates as $date) {
            $attendanceLabels[] = Carbon::parse($date)->translatedFormat('l');
            $attendanceData[] = (int) ($raw[$date] ?? 0);
        }

        $total = array_sum($attendanceData);
        $avg = $total / 7;
        $target = 100;

        $percentage = $target > 0
            ? round(($avg / $target) * 100, 1)
            : 0;

        return [
            'attendanceLabels' => $attendanceLabels,
            'attendanceData' => $attendanceData,
            'attendancePercentage' => $percentage,
        ];
    }

    private function getProduction(): array
    {
        $masuk = Data_stokmasuk::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray(); // 🔥 WAJIB

        $keluar = Data_stokkeluar::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray(); // 🔥 WAJIB

        $statuses = ['draft', 'posted', 'cancelled'];

        $productionData = [];
        $productionStats = [];

        foreach ($statuses as $status) {
            $total = (int) (($masuk[$status] ?? 0) + ($keluar[$status] ?? 0));

            $productionData[] = $total;
            $productionStats[$status] = $total;
        }

        return [
            'productionData' => $productionData, // array murni [0,1,2]
            'productionStats' => $productionStats,
        ];
    }

    private function getActivityAjax()
    {
        $recentTransaksi = Data_kartustok::with(['produk', 'user.dataDiri'])
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'html' => view('admin.dashboard.activity-table', compact('recentTransaksi'))->render()
        ]);
    }
}
