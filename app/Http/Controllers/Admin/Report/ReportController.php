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
        $tahun = $request->get('tahun', now()->year);

        $listTahun = collect(range(0, 2))
            ->map(fn($i) => now()->subYears($i)->year)
            ->toArray();

        /* =========================
        BULAN (DISTINCT FIX)
        ========================= */
        $bulan = DB::query()->fromSub(
            DB::table('data_stokmasuks')
                ->selectRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m') as bulan")
                ->where('status', 'posted')
                ->whereYear('tanggal_masuk', $tahun)
                ->unionAll(
                    DB::table('data_stokkeluars')
                        ->selectRaw("DATE_FORMAT(tanggal_keluar, '%Y-%m') as bulan")
                        ->where('status', 'posted')
                        ->whereYear('tanggal_keluar', $tahun)
                ),
            'bulan_union'
        )->select('bulan')->distinct();

        /* =========================
        STOK MASUK
        ========================= */
        $stokMasuk = DB::table('data_stokmasuks as sm')
            ->join('data_produks as p', 'p.id', '=', 'sm.produk_id')
            ->where('sm.status', 'posted')
            ->whereYear('sm.tanggal_masuk', $tahun)
            ->groupByRaw("DATE_FORMAT(sm.tanggal_masuk, '%Y-%m')")
            ->selectRaw("
            DATE_FORMAT(sm.tanggal_masuk, '%Y-%m') as bulan,
            SUM(sm.jumlah) as total_masuk,
            SUM(sm.jumlah * p.harga) as uang_masuk,
            COUNT(DISTINCT sm.kode_transaksi) as total_transaksi_masuk
        ");

        /* =========================
        STOK KELUAR
        ========================= */
        $stokKeluar = DB::table('data_stokkeluars as sk')
            ->join('data_produks as p', 'p.id', '=', 'sk.produk_id')
            ->where('sk.status', 'posted')
            ->whereYear('sk.tanggal_keluar', $tahun)
            ->groupByRaw("DATE_FORMAT(sk.tanggal_keluar, '%Y-%m')")
            ->selectRaw("
            DATE_FORMAT(sk.tanggal_keluar, '%Y-%m') as bulan,
            SUM(sk.jumlah) as total_keluar,
            SUM(sk.jumlah * p.harga) as uang_keluar,
            COUNT(DISTINCT sk.kode_transaksi) as total_transaksi_keluar
        ");

        /* =========================
        FINAL REPORT
        ========================= */
        $report = DB::query()
            ->fromSub($bulan, 'b')
            ->leftJoinSub($stokMasuk, 'masuk', fn($j) => $j->on('b.bulan', '=', 'masuk.bulan'))
            ->leftJoinSub($stokKeluar, 'keluar', fn($j) => $j->on('b.bulan', '=', 'keluar.bulan'))
            ->orderBy('b.bulan', 'desc')
            ->get([
                'b.bulan',
                DB::raw('COALESCE(masuk.total_transaksi_masuk,0) as total_transaksi_masuk'),
                DB::raw('COALESCE(keluar.total_transaksi_keluar,0) as total_transaksi_keluar'),
                DB::raw('COALESCE(masuk.total_masuk,0) as total_masuk'),
                DB::raw('COALESCE(keluar.total_keluar,0) as total_keluar'),
                DB::raw('COALESCE(masuk.uang_masuk,0) as uang_masuk'),
                DB::raw('COALESCE(keluar.uang_keluar,0) as uang_keluar'),
                DB::raw('(COALESCE(masuk.uang_masuk,0)-COALESCE(keluar.uang_keluar,0)) as total_uang'),
            ]);

        /* =========================
        TOTAL STOK (NO CACHE BUG)
        ========================= */
        $totalStok = DB::table('data_produks')->sum('stok');

        /* =========================
        CHART (FIX keyBy)
        ========================= */
        $chart = DB::table('data_stokmasuks as sm')
            ->join('data_produks as p', 'p.id', '=', 'sm.produk_id')
            ->where('sm.status', 'posted')
            ->whereYear('sm.tanggal_masuk', $tahun)
            ->groupByRaw("DATE_FORMAT(sm.tanggal_masuk, '%Y-%m')")
            ->orderBy('bulan', 'asc')
            ->get([
                DB::raw("DATE_FORMAT(sm.tanggal_masuk, '%Y-%m') as bulan"),
                DB::raw("SUM(sm.jumlah * p.harga) as total_uang")
            ])
            ->keyBy('bulan'); // ✅ SEKALI SAJA

        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $key = $bulan->format('Y-m');

            $labels[] = $bulan->translatedFormat('M');
            $data[] = (int) ($chart[$key]->total_uang ?? 0);
        }

        /* =========================
        KARTU STOK (NO N+1 + SAFE SEARCH)
        ========================= */
        $query = Data_kartustok::query()
            ->with([
                'produk:id,nama_produk,kode_produk,foto_produk',
                'user:id,username'
            ])
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhere('qty', 'like', "%$search%")
                    ->orWhereHas(
                        'produk',
                        fn($p) =>
                        $p->where('nama_produk', 'like', "%$search%")
                            ->orWhere('kode_produk', 'like', "%$search%")
                    )
                    ->orWhereHas(
                        'user',
                        fn($u) =>
                        $u->where('username', 'like', "%$search%")
                    );
            });
        }

        $kartu = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.report.table', compact('kartu'))->render(),
                'empty' => $kartu->isEmpty()
            ]);
        }

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
