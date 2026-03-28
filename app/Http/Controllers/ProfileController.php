<?php

namespace App\Http\Controllers;

use App\Models\Master_datadiri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $dataDiri = Master_datadiri::where('user_id', Auth::id())
            ->first();

        return view('profile', compact('dataDiri'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_wa' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto_diri' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // =========================
        // AMBIL / BUAT DATA DIRI
        // =========================
        $dataDiri = Master_datadiri::firstOrNew([
            'user_id' => $user->id
        ]);

        // =========================
        // HANDLE FOTO
        // =========================
        if ($request->hasFile('foto_diri')) {

            $file = $request->file('foto_diri');

            $namaFile = 'user_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            $tujuan = public_path('foto_profile');

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0777, true);
            }

            // hapus foto lama
            if ($dataDiri->foto_diri && file_exists(public_path('foto_profile/' . $dataDiri->foto_diri))) {
                unlink(public_path('foto_profile/' . $dataDiri->foto_diri));
            }

            $file->move($tujuan, $namaFile);

            $dataDiri->foto_diri = $namaFile;
        }

        // =========================
        // UPDATE DATA
        // =========================
        $dataDiri->nama_lengkap = $request->nama_lengkap;
        $dataDiri->alamat = $request->alamat;
        $dataDiri->no_wa = $request->no_wa;
        $dataDiri->jenis_kelamin = $request->jenis_kelamin;

        $dataDiri->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
