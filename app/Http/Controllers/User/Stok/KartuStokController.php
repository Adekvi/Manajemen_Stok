<?php

namespace App\Http\Controllers\User\Stok;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Data_kartustok;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    public function index(Request $request)
    {
        $query = Data_kartustok::with(['produk', 'user'])
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->when($request->filled('search'), function ($q) use ($request) {

                $search = trim($request->search);

                $q->where(function ($q2) use ($search) {
                    $q2->where('kode_transaksi', 'like', "%$search%")
                        ->orWhere('tanggal', 'like', "%$search%")
                        ->orWhere('qty', 'like', "%$search%")
                        ->orWhereHas(
                            'produk',
                            fn($p) =>
                            $p->where('nama_produk', 'like', "%$search%")
                                ->orWhere('kode_produk', 'like', "%$search%")
                        )
                        ->orWhereHas(
                            'user',
                            fn($u) =>
                            $u->where('username', 'like', "%$search%")
                        );
                });
            });
        }

        $kartu = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {

            $table = view('user.stok.kartustok.table', compact('kartu'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $kartu->count() === 0,
                'last_id' => $kartu->first()?->id
            ]);
        }

        return view('user.stok.kartustok.view', compact('kartu'));
    }

    public function show($id)
    {
        $item = Data_kartustok::with('produk')->findOrFail($id);

        return response()->json($item);
    }
}
