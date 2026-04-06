<?php

namespace App\Models;

use App\Models\Admin\HakAkses\Menu;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
