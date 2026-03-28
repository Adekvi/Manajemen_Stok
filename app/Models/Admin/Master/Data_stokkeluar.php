<?php

namespace App\Models\Admin\Master;

use App\Models\Data_produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Data_stokkeluar extends Model
{
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Data_produk::class, 'produk_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
