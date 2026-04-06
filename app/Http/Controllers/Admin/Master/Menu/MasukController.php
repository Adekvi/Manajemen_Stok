<?php

namespace App\Http\Controllers\Admin\Master\Menu;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokmasuk;
use App\Models\Data_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MasukController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_stokmasuk::with('produk', 'creator.dataDiri', 'poster')
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

        $recentTransaksi = Data_stokmasuk::with('produk', 'creator.dataDiri', 'poster')
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            // Request dari stok table
            if ($request->has('type') && $request->type === 'stok') {
                $table = view('admin.produk.masuk.table', compact('masuk'))->render();
                return response()->json([
                    'html' => $table,
                    'empty' => $masuk->isEmpty()
                ]);
            }

            // Request dari activity table (baru)
            if ($request->has('type') && $request->type === 'activity') {
                $activityHtml = view('admin.produk.masuk.activity', compact('recentTransaksi'))->render();
                return response()->json([
                    'html' => $activityHtml
                ]);
            }

            // Default (stok)
            $table = view('admin.produk.masuk.table', compact('masuk'))->render();
            return response()->json([
                'html' => $table,
                'empty' => $masuk->isEmpty()
            ]);
        }

        return view('admin.produk.masuk.stok', compact('masuk', 'produks', 'recentTransaksi'));
    }

    public function show($id)
    {
        $item = Data_stokmasuk::with('produk:id,nama_produk,kode_produk,harga,kategori,satuan,foto_produk,status')
            ->findOrFail($id);

        // Debug
        Log::info('Data Stok Masuk Detail:', $item->toArray());

        return response()->json($item);
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Data_stokmasuk::ownedByUser()->findOrFail($id);

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

        /*
        🔥 LOGIC PENTING
        */
        $item->status = $statusBaru;

        // ✅ Isi posted_by saat POSTED / CANCELLED
        if (in_array($statusBaru, ['posted', 'cancelled'])) {
            $item->posted_by = Auth::id();
        }

        // ❗ Optional: kalau balik ke draft, kosongkan lagi
        if ($statusBaru === 'draft') {
            $item->posted_by = null;
        }

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }
}
