<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use function Laravel\Prompts\number;

class MonthlyReportExport implements FromCollection, WithHeadings
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        // =========================
        // QUERY SAMA PERSIS DENGAN CONTROLLER
        // =========================
        $bulan = DB::table('data_stokmasuks')
            ->selectRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m') as bulan")
            ->where('status', 'posted')
            ->whereYear('tanggal_masuk', $this->tahun)

            ->union(
                DB::table('data_stokkeluars')
                    ->selectRaw("DATE_FORMAT(tanggal_keluar, '%Y-%m') as bulan")
                    ->where('status', 'posted')
                    ->whereYear('tanggal_keluar', $this->tahun)
            );

        $stokMasuk = DB::table('data_stokmasuks as sm')
            ->join('data_produks as p', 'p.id', '=', 'sm.produk_id')
            ->where('sm.status', 'posted')
            ->whereYear('sm.tanggal_masuk', $this->tahun)
            ->selectRaw("
                DATE_FORMAT(sm.tanggal_masuk, '%Y-%m') as bulan,
                SUM(sm.jumlah) as total_masuk,
                SUM(sm.jumlah * p.harga) as uang_masuk,
                COUNT(DISTINCT sm.kode_transaksi) as total_transaksi_masuk
            ")
            ->groupBy('bulan');

        $stokKeluar = DB::table('data_stokkeluars as sk')
            ->join('data_produks as p', 'p.id', '=', 'sk.produk_id')
            ->where('sk.status', 'posted')
            ->whereYear('sk.tanggal_keluar', $this->tahun)
            ->selectRaw("
                DATE_FORMAT(sk.tanggal_keluar, '%Y-%m') as bulan,
                SUM(sk.jumlah) as total_keluar,
                SUM(sk.jumlah * p.harga) as uang_keluar,
                COUNT(DISTINCT sk.kode_transaksi) as total_transaksi_keluar
            ")
            ->groupBy('bulan');

        $report = DB::query()
            ->fromSub($bulan, 'b')
            ->leftJoinSub($stokMasuk, 'masuk', fn($join) =>
            $join->on('b.bulan', '=', 'masuk.bulan'))
            ->leftJoinSub($stokKeluar, 'keluar', fn($join) =>
            $join->on('b.bulan', '=', 'keluar.bulan'))
            ->selectRaw("
                b.bulan,
                COALESCE(masuk.total_transaksi_masuk, 0) as masuk,
                COALESCE(keluar.total_transaksi_keluar, 0) as keluar,
                COALESCE(masuk.total_masuk, 0) as stok_masuk,
                COALESCE(keluar.total_keluar, 0) as stok_keluar,
                (COALESCE(masuk.uang_masuk, 0) - COALESCE(keluar.uang_keluar, 0)) as total_uang
            ")
            ->orderBy('b.bulan', 'desc')
            ->get();

        // mapping ke excel
        return $report->map(function ($item, $index) {

            $bulan = Carbon::parse($item->bulan . '-01')->translatedFormat('F Y');

            return [
                'No' => $index + 1,
                'Bulan' => $bulan,
                'Transaksi Masuk' => $item->masuk,
                'Transaksi Keluar' => $item->keluar,
                'Stok Masuk' => $item->stok_masuk,
                'Stok Keluar' => $item->stok_keluar,
                'Total Uang' => 'Rp ' . number_format($item->total_uang, 0, ',', '.'),
                'Status' => $item->stok_keluar > $item->stok_masuk ? 'Defisit' : 'Stabil',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Bulan',
            'Transaksi Masuk',
            'Transaksi Keluar',
            'Stok Masuk',
            'Stok Keluar',
            'Total Uang',
            'Status',
        ];
    }
}
