<?php

use App\Models\Master_info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('api')->group(function () {
    Route::get('/notifikasi', function () {
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return [];
        }
        return Master_info::where('status', 'aktif')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'waktu' => \Carbon\Carbon::parse($item->tgl)->diffForHumans()
                ];
            });
    });
});
