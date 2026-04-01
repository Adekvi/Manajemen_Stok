<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Master_info extends Model
{
    protected $guarded = [];

    public function readers()
    {
        return $this->belongsToMany(User::class, 'info_user_reads')
            ->withPivot('read_at')
            ->withTimestamps();
    }
}
