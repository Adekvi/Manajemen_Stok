<?php

namespace App\Http\Controllers\Admin\Master\Menu;

use App\Http\Controllers\Controller;
use App\Models\Data_produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_produk::orderBy('id', 'desc');

        if ($request->has('search') && $request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%$search%")
                    ->orWhere('kode_produk', 'like', "%$search%")
                    ->orWhere('satuan', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%")
                    ->orWhere('harga', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
            });
        }

        $produk = $query->paginate(10)->withQueryString();

        // jika ajax hanya kirim tabel
        if ($request->ajax()) {
            $isEmpty = $produk->isEmpty();
            $table = $isEmpty
                ? ''
                : view('admin.produk.pro.table', compact('produk'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $isEmpty
            ]);
        }

        return view('admin.produk.pro.index', compact('produk'));
    }

    public function generateKode()
    {
        $last = Data_produk::latest('id')->first();

        $number = $last
            ? (int) substr($last->kode_produk ?? 'PRO-00000', 4) + 1
            : 1;

        $kode = sprintf('PRO-%05d', $number);

        return response()->json([
            'success' => true,
            'kode'    => $kode
        ]);
    }

    public function show($id)
    {
        $item = Data_produk::findOrFail($id);
        return response()->json($item);
    }
}
