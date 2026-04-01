<?php

namespace App\Http\Controllers;

use App\Models\Data_produk;
use App\Models\Admin\Master\Data_stokmasuk;
use App\Models\Admin\Master\Data_stokkeluar;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function global(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (!$query || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 2 karakter',
                'data' => []
            ]);
        }

        $search = '%' . strtolower($query) . '%';

        // ========================
        // PRODUK (paling prioritas)
        // ========================
        $produk = Data_produk::select('id', 'nama_produk', 'kode_produk', 'kategori', 'stok', 'satuan')
            ->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_produk) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(kode_produk) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', [$search]);
            })
            ->limit(6)                    // naikkan sedikit biar lebih berguna
            ->get()
            ->map(fn($item) => [
                'id'       => $item->id,
                'type'     => 'produk',
                'title'    => $item->nama_produk,
                'subtitle' => 'Kode: ' . $item->kode_produk,
                'icon'     => 'package',
                'meta'     => 'Stok: ' . $item->stok . ' ' . ($item->satuan ?? ''),
            ]);

        // ========================
        // STOK MASUK
        // ========================
        $stokMasuk = Data_stokmasuk::with(['produk' => function ($query) {
            $query->select('id', 'nama_produk', 'kode_produk');   // hanya kolom yang dibutuhkan
        }])
            ->select('id', 'produk_id', 'tanggal_masuk', 'jumlah', 'keterangan')
            ->where(function ($q) use ($search) {
                $q->whereHas('produk', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(nama_produk) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(kode_produk) LIKE ?', [$search]);
                })
                    ->orWhereRaw('LOWER(keterangan) LIKE ?', [$search]);
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id'       => $item->id,
                'type'     => 'stok_masuk',
                'title'    => optional($item->produk)->nama_produk ?? 'Produk tidak ditemukan',
                'subtitle' => 'Masuk - ' . $item->tanggal_masuk,
                'icon'     => 'arrow-down-circle',
                'meta'     => 'Qty: ' . $item->jumlah,
            ]);

        // ========================
        // STOK KELUAR
        // ========================
        $stokKeluar = Data_stokkeluar::with(['produk' => function ($query) {
            $query->select('id', 'nama_produk', 'kode_produk');
        }])
            ->select('id', 'produk_id', 'tanggal_keluar', 'jumlah', 'keterangan')
            ->where(function ($q) use ($search) {
                $q->whereHas('produk', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(nama_produk) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(kode_produk) LIKE ?', [$search]);
                })
                    ->orWhereRaw('LOWER(keterangan) LIKE ?', [$search]);
            })
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'id'       => $item->id,
                'type'     => 'stok_keluar',
                'title'    => optional($item->produk)->nama_produk ?? 'Produk tidak ditemukan',
                'subtitle' => 'Keluar - ' . $item->tanggal_keluar,
                'icon'     => 'arrow-up-circle',
                'meta'     => 'Qty: ' . $item->jumlah,
            ]);

        // Merge + urutkan agar produk selalu di atas
        $results = collect()
            ->merge($produk)
            ->merge($stokMasuk)
            ->merge($stokKeluar);

        return response()->json([
            'success' => true,
            'data'    => $results->values(),   // reset key
            'count'   => $results->count()
        ]);
    }
}
