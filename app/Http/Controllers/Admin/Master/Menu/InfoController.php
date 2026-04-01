<?php

namespace App\Http\Controllers\Admin\Master\Menu;

use App\Http\Controllers\Controller;
use App\Models\Master_info;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function index(Request $request)
    {
        $query = Master_info::orderBy('id', 'desc');

        // filter status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('tgl', 'like', "%$search%");
            });
        }

        $info = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {

            $table = view('admin.master.lain.table', compact('info'))->render();

            return response()->json([
                'html' => $table,
                'empty' => $info->count() === 0
            ]);
        }

        // dd($info);

        return view('admin.master.lain.info', compact('info'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'nullable|string',
            'tgl' => 'nullable|date',
            'konten' => 'nullable',
            'status' => 'nullable|in:aktif,nonaktif'
        ]);

        Master_info::create([
            'judul' => $request->judul,
            'tgl' => $request->tgl,
            'konten' => $request->konten,
            'status' => $request->status ?? 'aktif'
        ]);

        return redirect()
            ->route('admin.master.menu.info')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    public function show($id)
    {
        $data = Master_info::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'nullable|string',
            'tgl' => 'nullable|date',
            'konten' => 'nullable',
            'status' => 'nullable|in:aktif,nonaktif'
        ]);

        $data = Master_info::findOrFail($id);

        $data->update([
            'judul' => $request->judul,
            'tgl' => $request->tgl,
            'konten' => $request->konten,
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.master.menu.info')
            ->with('success', 'Pengumuman berhasil diubah');
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = Master_info::findOrFail($id);

        $data->status = $request->status;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $data = Master_info::findOrFail($id);
        $data->delete();

        return redirect()
            ->route('admin.master.menu.info')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
