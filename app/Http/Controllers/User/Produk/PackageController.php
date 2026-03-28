<?php

namespace App\Http\Controllers\User\Produk;

use App\Http\Controllers\Controller;
use App\Models\Data_produk;
use Illuminate\Http\Request;

class PackageController extends Controller
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
                : view('user.produk.table', compact('produk'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $isEmpty,
                'hash' => md5($table)
            ]);
        }

        return view('user.produk.index', compact('produk'));
    }

    public function show($id)
    {
        $item = Data_produk::findOrFail($id);
        return response()->json($item);
    }
}
