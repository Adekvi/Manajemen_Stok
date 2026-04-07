<?php

namespace App\Http\Controllers\Admin\Master\Produk;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokmasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BarangMasukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:data_produks,id'
        ]);

        $transaksi = Data_stokmasuk::create([
            'kode_transaksi' => $this->generateKode(),
            'produk_id' => $request->produk_id,
            'jumlah' => 0,
            'tanggal_masuk' => now(),
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

            $prefix = 'SM-' . date('Ymd');

            $last = Data_stokmasuk::where('kode_transaksi', 'like', $prefix . '%')
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
                $stokMasuk = Data_stokmasuk::findOrFail($id);

                if ($stokMasuk->status === 'posted') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaksi yang sudah POSTED tidak boleh diubah'
                    ], 422);
                }

                $validated = $request->validate([
                    'jumlah' => 'required|integer|min:1',
                    'tanggal_masuk' => 'required|date',
                    'keterangan' => 'nullable|string|max:255',
                    'status' => 'required|in:draft,posted,cancelled',
                ]);

                $stokMasuk->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil diperbarui',
                    'data' => $stokMasuk
                ]);
            });
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $stokMasuk = Data_stokmasuk::findOrFail($id);

        if ($stokMasuk->status === 'posted') {

            return response()->json([
                'success' => false,
                'message' => 'Transaksi yang sudah POSTED tidak boleh dihapus'
            ], 422);
        }

        $stokMasuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
