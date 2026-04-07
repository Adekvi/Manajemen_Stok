<?php

namespace App\Http\Controllers;

use App\Models\Master_datadiri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $dataDiri = Master_datadiri::where('user_id', Auth::id())->first();

        return view('profile', compact('dataDiri'));
    }

    public function update(Request $request)
    {
        /* =====================
       VALIDASI
    ===================== */

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:1000',
            'no_wa' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto_diri' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        /* =====================
       AMBIL / BUAT DATA
    ===================== */

        $dataDiri = Master_datadiri::firstOrNew([
            'user_id' => $user->id
        ]);

        /* =====================
       FUNCTION UPLOAD AMAN
    ===================== */

        function uploadFoto($file, $path, $oldFile = null)
        {
            if (!$file->isValid()) {
                throw new \Exception('File tidak valid');
            }

            // Validasi MIME (anti fake file)
            $allowedMime = ['image/jpeg', 'image/png'];
            if (!in_array($file->getMimeType(), $allowedMime)) {
                throw new \Exception('Tipe file tidak diizinkan');
            }

            // Nama random (anti tebak & overwrite)
            $filename = Str::uuid() . '.' . $file->extension();

            // Buat folder jika belum ada (AMAN, bukan 0777)
            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), 0755, true);
            }

            // Hapus file lama
            if ($oldFile && File::exists(public_path($path . '/' . $oldFile))) {
                File::delete(public_path($path . '/' . $oldFile));
            }

            // Simpan file
            $file->move(public_path($path), $filename);

            return $filename;
        }

        /* =====================
       HANDLE FOTO
    ===================== */

        try {
            if ($request->hasFile('foto_diri')) {
                $dataDiri->foto_diri = uploadFoto(
                    $request->file('foto_diri'),
                    'foto_profile',
                    $dataDiri->foto_diri
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        /* =====================
       UPDATE DATA (SANITASI)
    ===================== */

        $dataDiri->fill([
            'nama_lengkap' => strip_tags($request->nama_lengkap),
            'alamat' => strip_tags($request->alamat),
            'no_wa' => strip_tags($request->no_wa),
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        $dataDiri->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
