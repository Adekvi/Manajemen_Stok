<?php

namespace App\Models\Admin\HakAkses;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
