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
        $query = Data_stokkeluar::with(['produk', 'creator', 'poster'])
            ->ownedByUser()
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
            $table = view('user.stok.keluar.table', compact('keluar'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $keluar->count() === 0
            ]);
        }

        return view('user.stok.keluar.keluar', compact('keluar', 'produks'));
    }

    public function show($id)
    {
        $item = Data_stokkeluar::with('produk')
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

            $item = Data_stokkeluar::ownedByUser()
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
        HANDLE STOCK
        */
            if ($statusLama === 'draft' && $statusBaru === 'posted') {

                $affected = Data_produk::where('id', $item->produk_id)
                    ->where('stok', '>=', $item->jumlah)
                    ->decrement('stok', $item->jumlah);

                if (!$affected) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak cukup'
                    ], 422);
                }
            }

            if ($statusLama === 'posted' && $statusBaru === 'cancelled') {
                Data_produk::where('id', $item->produk_id)
                    ->increment('stok', $item->jumlah);
            }

            $item->status = $statusBaru;
            $item->posted_by = in_array($statusBaru, ['posted', 'cancelled']) ? Auth::id() : null;
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

                $stokKeluar = Data_stokkeluar::ownedByUser()
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($stokKeluar->status === 'cancelled') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaksi sudah CANCELLED'
                    ], 422);
                }

                $validated = $request->validate([
                    'jumlah' => 'required|integer|min:1',
                    'tanggal_keluar' => 'required|date',
                    'keterangan' => 'nullable|string|max:255',
                    'status' => 'required|in:draft,posted,cancelled',
                ]);

                $statusLama = $stokKeluar->status;
                $statusBaru = $validated['status'];

                /*
            =====================
            CASE 1: DRAFT → POSTED
            =====================
            */
                if ($statusLama === 'draft' && $statusBaru === 'posted') {

                    $affected = Data_produk::where('id', $stokKeluar->produk_id)
                        ->where('stok', '>=', $validated['jumlah'])
                        ->decrement('stok', $validated['jumlah']);

                    if (!$affected) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok tidak mencukupi'
                        ], 422);
                    }

                    $validated['posted_by'] = Auth::id();
                }

                /*
            =====================
            CASE 2: POSTED → POSTED
            =====================
            */
                if ($statusLama === 'posted' && $statusBaru === 'posted') {

                    $selisih = $validated['jumlah'] - $stokKeluar->jumlah;

                    if ($selisih > 0) {
                        $affected = Data_produk::where('id', $stokKeluar->produk_id)
                            ->where('stok', '>=', $selisih)
                            ->decrement('stok', $selisih);

                        if (!$affected) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Stok tidak mencukupi'
                            ], 422);
                        }
                    } elseif ($selisih < 0) {
                        Data_produk::where('id', $stokKeluar->produk_id)
                            ->increment('stok', abs($selisih));
                    }
                }

                /*
            =====================
            CASE 3: POSTED → CANCELLED
            =====================
            */
                if ($statusLama === 'posted' && $statusBaru === 'cancelled') {

                    Data_produk::where('id', $stokKeluar->produk_id)
                        ->increment('stok', $stokKeluar->jumlah);

                    $validated['posted_by'] = Auth::id();
                }

                /*
            =====================
            CASE 4: POSTED → DRAFT
            =====================
            */
                if ($statusLama === 'posted' && $statusBaru === 'draft') {

                    Data_produk::where('id', $stokKeluar->produk_id)
                        ->increment('stok', $stokKeluar->jumlah);

                    $validated['posted_by'] = null;
                }

                $stokKeluar->update($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil update',
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
}
