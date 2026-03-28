<?php

namespace App\Http\Controllers\Admin\Master\Menu;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokmasuk;
use App\Models\Data_produk;
use Illuminate\Http\Request;

class MasukController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_stokmasuk::with('produk')
            ->latest();

        if ($request->has('search') && $request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('jumlah', 'like', "%$search%")
                    ->orWhere('tanggal_masuk', 'like', "%$search%")
                    ->orWhereHas('produk', function ($q2) use ($search) {
                        $q2->where('nama_produk', 'like', "%$search%")
                            ->orWhere('kode_produk', 'like', "%$search%");
                    });
            });
        }

        $masuk = $query->paginate(10)->withQueryString();

        $produks = Data_produk::orderBy('nama_produk')->get();

        if ($request->ajax()) {
            $table = view('admin.produk.masuk.table', compact('masuk'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $masuk->count() === 0
            ]);
        }

        return view('admin.produk.masuk.stok', compact('masuk', 'produks'));
    }

    public function show($id)
    {
        $item = Data_stokmasuk::with('produk:id,nama_produk,kode_produk,harga,kategori,satuan,foto_produk,status')
            ->findOrFail($id);
        return response()->json($item);
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Data_stokmasuk::findOrFail($id);

        $statusBaru = $request->status;
        $statusSekarang = $item->status;

        // VALIDASI STATUS
        if ($statusSekarang == 'posted') {

            if ($statusBaru != 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status yang sudah diposting hanya bisa diubah menjadi Cancelled.'
                ], 400);
            }
        }

        if ($statusSekarang == 'cancelled') {

            return response()->json([
                'success' => false,
                'message' => 'Transaksi yang sudah Cancel tidak dapat diubah lagi.'
            ], 400);
        }

        // UPDATE STATUS
        $item->status = $statusBaru;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }
}
