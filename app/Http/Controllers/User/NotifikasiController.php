<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Master_info;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->hasRole('user')) {
            return response()->json([]);
        }

        $data = Master_info::where('status', 'aktif')
            ->whereDoesntHave('readers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->konten,
                    'waktu' => $item->created_at->diffForHumans()
                ];
            });

        return response()->json($data);
    }


    public function all()
    {
        $user = Auth::user();

        $data = Master_info::where('status', 'aktif')
            ->with(['readers' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->konten,
                    'waktu' => \Carbon\Carbon::parse($item->tgl)->diffForHumans(),
                    'tgl_raw' => $item->tgl,
                    'is_read' => $item->readers->isNotEmpty()
                ];
            })
            ->sortBy([
                ['is_read', 'asc'],
                ['tgl_raw', 'desc']
            ])
            ->values();

        return response()->json($data);
    }

    public function read($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->readInfos()->syncWithoutDetaching([
            $id => ['read_at' => now()]
        ]);

        return response()->json(['success' => true]);
    }

    public function readAll()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $ids = Master_info::where('status', 'aktif')
            ->whereDoesntHave('readers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->pluck('id');

        $data = [];
        foreach ($ids as $id) {
            $data[$id] = ['read_at' => now()];
        }

        $user->readInfos()->syncWithoutDetaching($data);

        return response()->json(['success' => true]);
    }
}
