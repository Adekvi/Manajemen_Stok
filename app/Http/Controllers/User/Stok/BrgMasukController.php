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
        $request->validate([
            'status' => 'required|in:draft,posted,cancelled',
        ]);

        return DB::transaction(function () use ($request, $id) {

            $item = Data_stokmasuk::ownedByUser()
                ->lockForUpdate()
                ->findOrFail($id);

            $statusLama = $item->status;
            $statusBaru = $request->status;

            if ($statusLama === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa diubah lagi'
                ], 400);
            }

            if ($statusLama === 'posted' && $statusBaru !== 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa ke Cancelled'
                ], 400);
            }

            /*
                HANDLE STOK
            */
            if ($statusLama === 'draft' && $statusBaru === 'posted') {

                Data_produk::where('id', $item->produk_id)
                    ->increment('stok', $item->jumlah);
            }

            if ($statusLama === 'posted' && $statusBaru === 'cancelled') {

                Data_produk::where('id', $item->produk_id)
                    ->decrement('stok', $item->jumlah);
            }

            $item->status = $statusBaru;
            $item->posted_by = in_array($statusBaru, ['posted', 'cancelled'])
                ? Auth::id()
                : null;

            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        });
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
            return DB::transaction(function () use ($request, $id) {

                $stokMasuk = Data_stokmasuk::ownedByUser()
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($stokMasuk->status === 'cancelled') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaksi sudah CANCELLED'
                    ], 422);
                }

                $validated = $request->validate([
                    'jumlah' => 'required|integer|min:1',
                    'tanggal_masuk' => 'required|date',
                    'keterangan' => 'nullable|string|max:255',
                    'status' => 'required|in:draft,posted,cancelled',
                ]);

                $produkId = $stokMasuk->produk_id;
                $statusLama = $stokMasuk->status;
                $statusBaru = $validated['status'];

                /*
                    =====================
                    CASE 1: DRAFT → POSTED
                    =====================
                */
                if ($statusLama === 'draft' && $statusBaru === 'posted') {

                    Data_produk::where('id', $produkId)
                        ->increment('stok', $validated['jumlah']);

                    $validated['posted_by'] = Auth::id();
                }

                /*
                    =====================
                    CASE 2: POSTED → POSTED (EDIT)
                    =====================
                */
                if ($statusLama === 'posted' && $statusBaru === 'posted') {

                    $selisih = $validated['jumlah'] - $stokMasuk->jumlah;

                    if ($selisih != 0) {
                        Data_produk::where('id', $produkId)
                            ->increment('stok', $selisih);
                    }
                }

                /*
                    =====================
                    CASE 3: POSTED → CANCELLED
                    =====================
                */
                if ($statusLama === 'posted' && $statusBaru === 'cancelled') {

                    Data_produk::where('id', $produkId)
                        ->decrement('stok', $stokMasuk->jumlah);

                    $validated['posted_by'] = Auth::id();
                }

                /*
                    =====================
                    CASE 4: POSTED → DRAFT
                    =====================
                */
                if ($statusLama === 'posted' && $statusBaru === 'draft') {

                    Data_produk::where('id', $produkId)
                        ->decrement('stok', $stokMasuk->jumlah);

                    $validated['posted_by'] = null;
                }

                $stokMasuk->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil update',
                    'data' => $stokMasuk
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
}
