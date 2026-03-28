<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\MonthlyReportExport;
use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_kartustok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);

        $listTahun = [];

        for ($i = 0; $i < 3; $i++) {
            $listTahun[] = Carbon::now()->subYears($i)->year;
        }

        // =========================
        // AMBIL SEMUA BULAN (MASUK + KELUAR)
        // =========================
        $bulan = DB::table('data_stokmasuks')
            ->selectRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m') as bulan")
            ->where('status', 'posted')
            ->whereYear('tanggal_masuk', $tahun)

            ->union(

                DB::table('data_stokkeluars')
                    ->selectRaw("DATE_FORMAT(tanggal_keluar, '%Y-%m') as bulan")
                    ->where('status', 'posted')
                    ->whereYear('tanggal_keluar', $tahun)

            );

        // =========================
        // STOK MASUK
        // =========================
        $stokMasuk = DB::table('data_stokmasuks as sm')
            ->join('data_produks as p', 'p.id', '=', 'sm.produk_id')
            ->where('sm.status', 'posted')
            ->selectRaw("
                    DATE_FORMAT(sm.tanggal_masuk, '%Y-%m') as bulan,

                    SUM(sm.jumlah) as total_masuk,
                    SUM(sm.jumlah * p.harga) as uang_masuk,

                    -- 🔥 FIX DISINI
                    COUNT(DISTINCT sm.kode_transaksi) as total_transaksi_masuk,

                    GROUP_CONCAT(DISTINCT sm.kode_transaksi ORDER BY sm.id DESC SEPARATOR ', ') as kode_masuk
                ")
            ->whereYear('sm.tanggal_masuk', $tahun)
            ->groupBy('bulan');

        // =========================
        // STOK KELUAR
        // =========================
        $stokKeluar = DB::table('data_stokkeluars as sk')
            ->join('data_produks as p', 'p.id', '=', 'sk.produk_id')
            ->where('sk.status', 'posted')
            ->selectRaw("
                    DATE_FORMAT(sk.tanggal_keluar, '%Y-%m') as bulan,

                    SUM(sk.jumlah) as total_keluar,
                    SUM(sk.jumlah * p.harga) as uang_keluar,

                    -- 🔥 FIX DISINI
                    COUNT(DISTINCT sk.kode_transaksi) as total_transaksi_keluar,

                    GROUP_CONCAT(DISTINCT sk.kode_transaksi ORDER BY sk.id DESC SEPARATOR ', ') as kode_keluar
                ")
            ->whereYear('sk.tanggal_keluar', $tahun)
            ->groupBy('bulan');

        // =========================
        // FINAL REPORT
        // =========================
        $report = DB::query()
            ->fromSub($bulan, 'b')
            ->leftJoinSub($stokMasuk, 'masuk', function ($join) {
                $join->on('b.bulan', '=', 'masuk.bulan');
            })
            ->leftJoinSub($stokKeluar, 'keluar', function ($join) {
                $join->on('b.bulan', '=', 'keluar.bulan');
            })
            ->selectRaw("
                    b.bulan,

                    COALESCE(masuk.total_transaksi_masuk, 0) as total_transaksi_masuk,
                    COALESCE(keluar.total_transaksi_keluar, 0) as total_transaksi_keluar,

                    COALESCE(masuk.kode_masuk, '-') as kode_masuk,
                    COALESCE(keluar.kode_keluar, '-') as kode_keluar,

                    COALESCE(masuk.total_masuk, 0) as total_masuk,
                    COALESCE(keluar.total_keluar, 0) as total_keluar,

                    COALESCE(masuk.uang_masuk, 0) as uang_masuk,
                    COALESCE(keluar.uang_keluar, 0) as uang_keluar,

                    (COALESCE(masuk.uang_masuk, 0) - COALESCE(keluar.uang_keluar, 0)) as total_uang
                ")
            ->groupBy(
                'b.bulan',
                'masuk.total_transaksi_masuk',
                'keluar.total_transaksi_keluar',
                'masuk.kode_masuk',
                'keluar.kode_keluar',
                'masuk.total_masuk',
                'keluar.total_keluar',
                'masuk.uang_masuk',
                'keluar.uang_keluar'
            )
            ->orderBy('b.bulan', 'desc')
            ->get();

        // =========================
        // TOTAL STOK GLOBAL
        // =========================
        $totalStok = DB::table('data_produks')->sum('stok');

        // =========================
        // DATA CHART 6 BULAN TERAKHIR
        // =========================
        $start = Carbon::now()->subMonths(5)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $chart = DB::table('data_stokmasuks as sm')
            ->join('data_produks as p', 'p.id', '=', 'sm.produk_id')
            ->where('sm.status', 'posted')
            ->whereYear('sm.tanggal_masuk', $tahun)
            ->selectRaw("
                    DATE_FORMAT(sm.tanggal_masuk, '%Y-%m') as bulan,
                    SUM(sm.jumlah * p.harga) as total_uang
                ")
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // buat 6 bulan default (biar tidak bolong)
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);

            $key = $bulan->format('Y-%m');

            $labels[] = $bulan->translatedFormat('M');

            $found = $chart->firstWhere('bulan', $key);

            $data[] = $found ? (int) $found->total_uang : 0;
        }

        // Stok
        $query = Data_kartustok::with(['produk', 'user'])
            ->orderBy('id', 'desc');

        // 🔥 APPLY DATE FILTER
        $dateFilter = $this->getDateFilter($request);

        if ($dateFilter) {
            $query->whereBetween('tanggal', [
                $dateFilter['start'],
                $dateFilter['end']
            ]);
        }

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhere('qty', 'like', "%$search%")
                    ->orWhereHas('produk', function ($p) use ($search) {
                        $p->where('nama_produk', 'like', "%$search%")
                            ->orWhere('kode_produk', 'like', "%$search%");
                    })
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', "%$search%");
                    });
            });
        }

        $kartu = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {

            $table = view('admin.report.table', compact('kartu'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $kartu->count() === 0,
                'last_id' => $kartu->first()?->id
            ]);
        }

        // dd($kartu);

        return view('admin.report.view', compact(
            'report',
            'totalStok',
            'labels',
            'data',
            'listTahun',
            'tahun',
            'kartu'
        ));
    }

    private function getDateFilter($request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {
            return [
                'start' => Carbon::parse($start)->startOfDay(),
                'end'   => Carbon::parse($end)->endOfDay(),
            ];
        }

        return null;
    }

    // EXPORT DATA BULANAN
    public function exportBulanan(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $fileName = "laporan-bulanan-{$tahun}-" . now()->format('His') . ".xlsx";

        return Excel::download(
            new MonthlyReportExport($tahun),
            $fileName
        );
    }

    // EXPORT DATA KARTU STOK 
    public function export(Request $request)
    {
        return Excel::download(
            new ReportExport($request),
            $this->generateFileName($request)
        );
    }

    private function generateFileName($request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        if ($start && $end) {

            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            $diffDays = $startDate->diffInDays($endDate);

            if ($diffDays === 0) {
                $name = 'laporan-hari-ini';
            } elseif ($diffDays <= 7) {
                $name = 'laporan-mingguan';
            } elseif ($diffDays <= 31) {
                $name = 'laporan-bulanan';
            } elseif ($diffDays <= 93) {
                $name = 'laporan-3-bulan';
            } elseif ($diffDays <= 186) {
                $name = 'laporan-6-bulan';
            } elseif ($diffDays <= 366) {
                $name = 'laporan-tahunan';
            } else {
                $name = 'laporan-' .
                    $startDate->format('d-m-Y') .
                    '_sampai_' .
                    $endDate->format('d-m-Y');
            }
        } elseif ($request->search) {

            $name = 'laporan-search-' . str_replace(' ', '-', $request->search);
        } else {

            $name = 'laporan-semua-data';
        }

        // 🔥 final filename
        return $name . '-' . now()->format('His') . '.xlsx';
    }
}
