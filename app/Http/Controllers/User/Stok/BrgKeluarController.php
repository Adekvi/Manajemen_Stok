<?php

namespace App\Http\Controllers\User\Stok;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Data_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrgKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_stokkeluar::with('produk')
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

        if ($request->ajax()) {
            $table = view('user.stok.table', compact('keluar'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $keluar->count() === 0
            ]);
        }

        return view('user.stok.keluar', compact('keluar', 'produks'));
    }

    public function show($id)
    {
        $item = Data_stokkeluar::with('produk:id,nama_produk,kode_produk,harga,kategori,satuan,foto_produk,status')
            ->findOrFail($id);
        return response()->json($item);
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Data_stokkeluar::findOrFail($id);

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

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:data_produks,id'
        ]);

        $transaksi = Data_stokkeluar::create([
            'kode_transaksi' => $this->generateKode(),
            'produk_id' => $request->produk_id,
            'jumlah' => 0,
            'tanggal_keluar' => now(),
            'status' => 'draft',
            'created_by' => Auth::user()->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        try {

            return DB::transaction(function () use ($request, $id) {

                $stokKeluar = Data_stokkeluar::lockForUpdate()->findOrFail($id);

                // ❌ Tidak boleh ubah jika sudah POSTED
                if ($stokKeluar->status === 'posted') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaksi yang sudah POSTED tidak boleh diubah'
                    ], 422);
                }

                $validated = $request->validate([
                    'jumlah' => 'required|integer|min:1',
                    'tanggal_keluar' => 'required|date',
                    'keterangan' => 'nullable|string|max:255',
                    'status' => 'required|in:draft,posted,cancelled',
                ]);

                $produk = Data_produk::lockForUpdate()->find($stokKeluar->produk_id);

                if (!$produk) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Produk tidak ditemukan'
                    ], 404);
                }

                /*
            VALIDASI STOK
            */
                if ($validated['status'] === 'posted') {

                    if ($validated['jumlah'] > $produk->stok) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok tidak mencukupi'
                        ], 422);
                    }

                    // ✅ Catat user yang POST
                    $validated['posted_by'] = Auth::user()->id;
                }

                /*
            UPDATE DATA
            */
                $stokKeluar->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil diperbarui',
                    'data' => $stokKeluar
                ]);
            });
        } catch (\Exception $e) {

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    private function generateKode()
    {
        $prefix = 'SK-' . date('Ymd');

        $last = Data_stokkeluar::where('kode_transaksi', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        if (!$last) {
            return $prefix . '-0001';
        }

        $number = intval(substr($last->kode_transaksi, -4)) + 1;

        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
