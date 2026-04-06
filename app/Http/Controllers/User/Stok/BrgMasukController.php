<?php

namespace App\Http\Controllers\User\Stok;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_stokmasuk;
use App\Models\Data_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrgMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_stokmasuk::with(['produk', 'creator', 'poster'])
            ->ownedByUser()
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
            $table = view('user.stok.masuk.table', compact('masuk'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $masuk->count() === 0
            ]);
        }

        return view('user.stok.masuk.view', compact('masuk', 'produks'));
    }

    public function show($id)
    {
        $item = Data_stokmasuk::with('produk')
            ->ownedByUser()
            ->findOrFail($id);

        return response()->json($item);
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Data_stokmasuk::ownedByUser()
            ->findOrFail($id);

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
            'created_by' => Auth::id(),
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
                ->lockForUpdate() // 🔥 KUNCI DATA
                ->orderBy('kode_transaksi', 'desc') // ✅ bukan latest()
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

            DB::beginTransaction();

            $stokMasuk = Data_stokmasuk::ownedByUser()
                ->findOrFail($id);

            // ❌ Tidak boleh ubah jika sudah POSTED
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

            /*
        🔥 HANDLE STATUS POSTED
        */
            if ($validated['status'] === 'posted') {

                // ✅ hanya set jika belum pernah
                if (!$stokMasuk->posted_by) {
                    $validated['posted_by'] = Auth::id();
                }

                // 🔥 (optional) update stok produk
                $produk = Data_produk::lockForUpdate()->find($stokMasuk->produk_id);

                if ($produk) {
                    $produk->stok += $validated['jumlah'];
                    $produk->save();
                }
            }

            /*
        UPDATE DATA
        */
            $stokMasuk->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui',
                'data' => $stokMasuk
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
}
