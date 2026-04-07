<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_kartustok;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Admin\Master\Data_stokmasuk;
use Illuminate\Http\Request;
use App\Models\Data_produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
            $this->getRecentTransaksi(),
        ));
    }

    private function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // PRODUK
        $produk = Data_produk::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as hari_ini,
            SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as kemarin
        ", [$today, $yesterday])->first();

        // STOK MASUK
        $stokMasuk = Data_stokmasuk::selectRaw("
            COUNT(CASE WHEN status = 'posted' THEN 1 END) as total,
            COUNT(CASE WHEN status = 'posted' AND DATE(tanggal_masuk) = ? THEN 1 END) as hari_ini
        ", [$today])->first();

        // STOK KELUAR
        $stokKeluar = Data_stokkeluar::selectRaw("
            COUNT(CASE WHEN status = 'posted' THEN 1 END) as total,
            COUNT(CASE WHEN status = 'posted' AND DATE(tanggal_keluar) = ? THEN 1 END) as hari_ini,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
        ", [$today])->first();

        // HITUNG PERSEN
        $produkHariini = $produk->hari_ini;
        $produkKemarin = $produk->kemarin;

        if ($produkKemarin == 0 && $produkHariini == 0) {
            $persenProduk = 0;
        } elseif ($produkKemarin == 0) {
            $persenProduk = 100;
        } elseif ($produkHariini == 0) {
            $persenProduk = 0;
        } else {
            $persenProduk = round((($produkHariini - $produkKemarin) / $produkKemarin) * 100, 1);
        }

        return [
            'produkAll' => $produk->total,
            'ttlMasuk' => $stokMasuk->total,
            'ttlKeluar' => $stokKeluar->total,
            'stokCancel' => $stokKeluar->cancelled,

            'produkHariini' => $produkHariini,
            'stokMasukHariIni' => $stokMasuk->hari_ini,
            'stokKeluarHariIni' => $stokKeluar->hari_ini,

            'persenProduk' => $persenProduk,
        ];
    }

    private function getRecentTransaksi(): array
    {
        return [
            'recentTransaksi' => $this->queryRecentTransaksi()
        ];
    }

    private function queryRecentTransaksi()
    {
        return Data_kartustok::query()
            ->with([
                'produk:id,nama_produk,kode_produk',
                'user:id,username',
                'user.dataDiri:user_id,foto_diri'
            ])
            ->ownedByUser()
            ->latest()
            ->limit(5)
            ->get([
                'id',
                'produk_id',
                'user_id',
                'qty',
                'tipe',
                'keterangan',
                'created_at'
            ]);
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
        $cacheKey = 'activity_' . Auth::id();

        $recentTransaksi = cache()->remember($cacheKey, 10, function () {
            return $this->queryRecentTransaksi();
        });

        return response()->json([
            'html' => view('admin.dashboard.activity-table', compact('recentTransaksi'))->render()
        ]);
    }

    public function getStatsAjax()
    {
        return response()->json($this->getStats());
    }
}
