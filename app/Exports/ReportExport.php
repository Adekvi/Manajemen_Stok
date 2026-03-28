<?php

namespace App\Exports;

use App\Models\Admin\Master\Data_kartustok;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    private function getDateFilter()
    {
        if ($this->request->start_date && $this->request->end_date) {
            return [
                'start' => Carbon::parse($this->request->start_date)->startOfDay(),
                'end'   => Carbon::parse($this->request->end_date)->endOfDay(),
            ];
        }

        return null;
    }

    public function collection()
    {
        $query = Data_kartustok::with(['produk', 'user'])
            ->orderBy('id', 'desc');

        // 🔥 FILTER TANGGAL
        $dateFilter = $this->getDateFilter();

        if ($dateFilter) {
            $query->whereBetween('tanggal', [
                $dateFilter['start'],
                $dateFilter['end']
            ]);
        }

        // 🔍 SEARCH
        if ($this->request->search) {

            $search = $this->request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhereHas('produk', fn($p) =>
                    $p->where('nama_produk', 'like', "%$search%"))
                    ->orWhereHas('user', fn($u) =>
                    $u->where('username', 'like', "%$search%"));
            });
        }

        return $query->get()->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama' => $item->user->username ?? 'Admin',
                'Produk' => $item->produk->nama_produk ?? '-',
                'Kode Produk' => $item->produk->kode_produk ?? '-',
                'Kode Transaksi' => $item->kode_transaksi ?? '-',
                'Tanggal' => Carbon::parse($item->tanggal)->translatedFormat('d M Y'),
                'Tipe' => $item->tipe,
                'Jumlah' => $item->qty,
                'Stok Sebelum' => $item->stok_sebelum,
                'Stok Setelah' => $item->stok_sesudah,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Produk',
            'Kode Produk',
            'Kode Transaksi',
            'Tanggal',
            'Tipe',
            'Jumlah',
            'Stok Sebelum',
            'Stok Setelah',
        ];
    }
}
