<?php

namespace App\Models\Admin\Master;

use App\Models\Data_produk;
use Illuminate\Database\Eloquent\Model;

class Data_stokmasuk extends Model
{
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Data_produk::class, 'produk_id');
    }
}
