<?php

namespace App\Models;

use App\Models\Admin\Master\Data_kartustok;
use App\Models\Admin\Master\Data_stokkeluar;
use App\Models\Admin\Master\Data_stokmasuk;
use Illuminate\Database\Eloquent\Model;

class Data_produk extends Model
{
    protected $guarded = [];

    public function masuk()
    {
        return $this->hasMany(Data_stokmasuk::class, 'produk_id');
    }

    public function keluar()
    {
        return $this->hasMany(Data_stokkeluar::class, 'produk_id');
    }

    public function kartu()
    {
        return $this->hasMany(Data_kartustok::class, 'produk_id');
    }

    protected static function booted()
    {
        static::creating(function ($produk) {
            if (!empty($produk->kode_produk)) {
                return; // sudah diisi manual → skip
            }

            $last = self::lockForUpdate()->latest('id')->first();

            $number = $last
                ? (int) substr($last->kode_produk ?? 'PRO-00000', 4) + 1
                : 1;

            $newCode = sprintf('PRO-%05d', $number);

            // Optional: double check (paranoid mode)
            while (self::where('kode_produk', $newCode)->exists()) {
                $number++;
                $newCode = sprintf('PRO-%05d', $number);
            }

            $produk->kode_produk = $newCode;
        });
    }
}
