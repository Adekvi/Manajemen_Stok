<?php

namespace App\Http\Controllers;

use App\Models\Master_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Master_setting::first();

        if (!$setting) {
            $setting = Master_setting::create([
                'nama_toko' => '',
                'email' => '',
                'no_telepon' => '',
                'alamat' => '',
                'rekening_bank' => [],
                'jam_operasional' => [],
            ]);
        }

        return view('setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Master_setting::first() ?? new Master_setting();

        /* =====================
       VALIDASI
    ===================== */

        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'qris' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_toko' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        /* =====================
       FUNCTION UPLOAD AMAN
    ===================== */

        function uploadFile($file, $path, $oldFile = null)
        {
            if (!$file->isValid()) {
                throw new \Exception('File tidak valid');
            }

            // Validasi MIME (extra layer)
            $allowedMime = ['image/jpeg', 'image/png'];
            if (!in_array($file->getMimeType(), $allowedMime)) {
                throw new \Exception('Tipe file tidak diizinkan');
            }

            // Generate nama random (anti overwrite & injection)
            $filename = Str::uuid() . '.' . $file->extension();

            // Pastikan folder ada
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
            LOGO
        ===================== */

        if ($request->hasFile('logo')) {
            $setting->logo = uploadFile(
                $request->file('logo'),
                'setting/logo',
                $setting->logo
            );
        }

        /* =====================
            QRIS
        ===================== */

        if ($request->hasFile('qris')) {
            $setting->qris = uploadFile(
                $request->file('qris'),
                'setting/qris',
                $setting->qris
            );
        }

        /* =====================
            REKENING BANK
        ===================== */

        $rekening = [];

        if ($request->nama_bank) {
            foreach ($request->nama_bank as $key => $bank) {
                $rekening[] = [
                    'bank' => strip_tags($bank),
                    'rekening' => strip_tags($request->no_rekening[$key] ?? ''),
                    'nama' => strip_tags($request->atas_nama[$key] ?? ''),
                ];
            }
        }

        /* =====================
            JAM OPERASIONAL
        ===================== */

        $jam = [
            'senin_jumat' => [
                'buka' => $request->senin_buka,
                'tutup' => $request->senin_tutup
            ],
            'sabtu' => [
                'buka' => $request->sabtu_buka,
                'tutup' => $request->sabtu_tutup
            ],
            'minggu' => [
                'tutup' => $request->minggu_tutup ? true : false
            ]
        ];

        /* =====================
            SIMPAN
        ===================== */

        $setting->fill([
            'nama_toko' => $request->nama_toko,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'rekening_bank' => $rekening,
            'bank_active' => $request->boolean('bank_active'),
            'qris_active' => $request->boolean('qris_active'),
            'cash_active' => $request->boolean('cash_active'),
            'jam_operasional' => $jam,
        ]);

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Setting berhasil diperbarui'
        ]);
    }
}
