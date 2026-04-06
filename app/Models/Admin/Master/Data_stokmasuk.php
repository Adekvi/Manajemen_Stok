<?php

namespace App\Models\Admin\Master;

use App\Models\Data_produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Data_stokmasuk extends Model
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

    public function scopeOwnedByUser($query)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return $query;
        }

        if (!$user->hasRole('admin')) {
            $query->where('created_by', $user->id);
        }

        return $query;
    }
}
