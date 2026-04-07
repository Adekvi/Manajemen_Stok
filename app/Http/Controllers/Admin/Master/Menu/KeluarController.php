<?php

namespace App\Http\Controllers\Admin\Master\Menu;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Data_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_stokkeluar::with('produk', 'creator.dataDiri', 'poster')
            ->latest();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jumlah', 'like', "%$search%")
                    ->orWhere('tanggal_keluar', 'like', "%$search%")
                    ->orWhereHas('produk', function ($q2) use ($search) {
                        $q2->where('nama_produk', 'like', "%$search%")
                            ->orWhere('kode_produk', 'like', "%$search%");
                    });
            });
        }

        $keluar = $query->paginate(10)->withQueryString();

        $produks = Data_produk::orderBy('nama_produk')->get();

        $recentTransaksi = Data_stokkeluar::with('produk', 'creator.dataDiri', 'poster') // tambah poster kalau perlu
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            // Request dari stok table
            if ($request->has('type') && $request->type === 'stok') {
                $table = view('admin.produk.keluar.table', compact('keluar'))->render();
                return response()->json([
                    'html' => $table,
                    'empty' => $keluar->isEmpty()
                ]);
            }

            // Request dari activity table (baru)
            if ($request->has('type') && $request->type === 'activity') {
                $activityHtml = view('admin.produk.keluar.activity', compact('recentTransaksi'))->render();
                return response()->json([
                    'html' => $activityHtml
                ]);
            }

            // Default (stok)
            $table = view('admin.produk.keluar.table', compact('keluar'))->render();
            return response()->json([
                'html' => $table,
                'empty' => $keluar->isEmpty()
            ]);
        }

        return view('admin.produk.keluar.view', compact('keluar', 'produks', 'recentTransaksi'));
    }

    public function show($id)
    {
        $item = Data_stokkeluar::with([
            'produk:id,nama_produk,kode_produk,harga,kategori,satuan,foto_produk,status',
            'creator:id,username',
            'poster:id,username'
        ])->findOrFail($id);

        return response()->json($item);
    }

    public function updateStatus(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $item = Data_stokkeluar::lockForUpdate()->findOrFail($id);

            $statusBaru = $request->status;
            $statusSekarang = $item->status;

            if ($statusSekarang === 'posted' && $statusBaru !== 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa ke Cancelled'
                ], 400);
            }

            if ($statusSekarang === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa diubah lagi'
                ], 400);
            }

            $item->status = $statusBaru;

            if (in_array($statusBaru, ['posted', 'cancelled'])) {
                $item->posted_by = Auth::id();
            } else {
                $item->posted_by = null;
            }

            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        });
    }
}
