<?php

namespace App\Http\Controllers;

use App\Models\Master_setting;
use Illuminate\Http\Request;

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

        $setting = Master_setting::first();

        if (!$setting) {
            $setting = new Master_setting();
        }

        /* =====================
           LOGO UPLOAD
        ===================== */

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');

            $namaFile = time() . '_' . $file->getClientOriginalName();

            $tujuan = public_path('setting/logo');

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0755, true);
            }

            // hapus logo lama
            if ($setting->logo && file_exists(public_path('setting/logo/' . $setting->logo))) {
                unlink(public_path('setting/logo/' . $setting->logo));
            }

            $file->move($tujuan, $namaFile);

            $setting->logo = $namaFile;
        }

        /* =====================
           QRIS UPLOAD
        ===================== */

        if ($request->hasFile('qris')) {

            $file = $request->file('qris');

            $namaFile = time() . '_' . $file->getClientOriginalName();

            $tujuan = public_path('setting/qris');

            if (!file_exists($tujuan)) {
                mkdir($tujuan, 0755, true);
            }

            // hapus file lama
            if ($setting->qris && file_exists(public_path('setting/qris/' . $setting->qris))) {
                unlink(public_path('setting/qris/' . $setting->qris));
            }

            $file->move($tujuan, $namaFile);

            $setting->qris = $namaFile;
        }

        /* =====================
           REKENING BANK
        ===================== */

        $rekening = [];

        if ($request->nama_bank) {

            foreach ($request->nama_bank as $key => $bank) {

                $rekening[] = [
                    'bank' => $bank,
                    'rekening' => $request->no_rekening[$key] ?? null,
                    'nama' => $request->atas_nama[$key] ?? null
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
           SIMPAN DATA
        ===================== */

        $setting->nama_toko = $request->nama_toko;
        $setting->email = $request->email;
        $setting->no_telepon = $request->no_telepon;
        $setting->alamat = $request->alamat;

        $setting->rekening_bank = $rekening;

        $setting->bank_active = $request->bank_active ? true : false;
        $setting->qris_active = $request->qris_active ? true : false;
        $setting->cash_active = $request->cash_active ? true : false;

        $setting->jam_operasional = $jam;

        $setting->save();

        return response()->json([
            'success' => true,
            'message' => 'Setting berhasil diperbarui'
        ]);
    }
}
