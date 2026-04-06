<?php

namespace App\Http\Controllers\Admin\Master\Produk;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Data_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BarangKeluarController extends Controller
{
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

    private function generateKode()
    {
        return DB::transaction(function () {

            $prefix = 'SK-' . date('Ymd');

            $last = Data_stokkeluar::where('kode_transaksi', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderBy('kode_transaksi', 'desc')
                ->first();

            if (!$last) {
                return $prefix . '-0001';
            }

            $number = (int) substr($last->kode_transaksi, -4) + 1;

            return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
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

    public function destroy($id)
    {
        $stokKeluar = Data_stokkeluar::findOrFail($id);

        if ($stokKeluar->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya transaksi DRAFT yang boleh dihapus'
            ], 422);
        }

        $stokKeluar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
