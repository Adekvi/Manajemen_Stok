<?php

namespace App\Models\Admin\Master;

use App\Models\Data_produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Data_kartustok extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Data_produk::class, 'produk_id');
    }

    public function referensi()
    {
        return $this->morphTo(null, 'referensi_tipe', 'referensi_id');
    }
}
