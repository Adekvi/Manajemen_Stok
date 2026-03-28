<?php

namespace App\Http\Controllers\Admin\Master\Produk;

use App\Http\Controllers\Controller;
use App\Models\Data_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataProdukController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk'     => 'required|string|max:20',
            'nama_produk'     => 'required|string|max:255',
            'foto_produk'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'satuan'          => 'nullable|string|max:50',
            'stok'            => 'required|numeric|min:0',
            'kategori'        => 'nullable|string|max:100',
            'harga'           => 'nullable|numeric|min:0',
            'keterangan'      => 'nullable|string|max:255',
            'status'          => 'nullable|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('foto_produk')) {

            $file = $request->file('foto_produk');

            $namaFile = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('produk/'), $namaFile);

            $validated['foto_produk'] = $namaFile;
        }

        // Simpan stok masuk
        $produk = Data_produk::create($validated);

        return redirect()
            ->route('admin.master.produk')
            ->with('success', "Produk {$produk->nama_produk} berhasil ditambahkan (Kode: {$produk->kode_produk})");
    }

    public function update(Request $request, $id)
    {
        try {

            DB::transaction(function () use ($request, $id, &$produk) {

                $produk = Data_produk::findOrFail($id);

                $validated = $request->validate([
                    'kode_produk'     => 'required|string|max:20',
                    'nama_produk'     => 'required|string|max:255',
                    'foto_produk'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'satuan'          => 'nullable|string|max:50',
                    'stok'            => 'required|numeric|min:0',
                    'kategori'        => 'nullable|string|max:100',
                    'harga'           => 'nullable|numeric|min:0',
                    'keterangan'      => 'nullable|string|max:255',
                    'status'          => 'nullable|in:aktif,nonaktif',
                ]);

                // Upload foto baru
                if ($request->hasFile('foto_produk')) {

                    if ($produk->foto_produk) {
                        $oldPath = public_path('produk/' . $produk->foto_produk);
                        if (file_exists($oldPath)) unlink($oldPath);
                    }

                    $file = $request->file('foto_produk');
                    $namaFile = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('produk/'), $namaFile);

                    $validated['foto_produk'] = $namaFile;
                }

                // update stok masuk
                $produk->update($validated);
            });

            return response()->json([
                'success' => true,
                'message' => "Data produk berhasil diperbarui.",
                'data'    => $produk
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            Log::error('Update produk gagal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat update data'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $produk = Data_produk::findOrFail($id);

        if ($produk->foto_produk && file_exists(public_path('produk/' . $produk->foto_produk))) {
            unlink(public_path('produk/' . $produk->foto_produk));
        }

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
