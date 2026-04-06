<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Admin\HakAkses\Menu;
use App\Models\Admin\Master\Data_kartustok;
use App\Models\Admin\Master\Data_stokkeluar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_online'     => 'boolean',
        'is_active'     => 'boolean',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password'          => 'hashed',
    //         'is_online'         => 'boolean',
    //         'is_active'         => 'boolean',
    //     ];
    // }

    // LOGIN AUTH
    public function isActive(): bool
    {
        return (bool) $this->is_active;   // pakai (bool) agar lebih aman
    }

    // LOGIN ROLE
    public function hasRole(string $roleName): bool
    {
        return $this->roles()
            ->where('name', $roleName)
            ->exists();
    }

    public function getPrimaryRole(): ?string
    {
        if ($this->hasRole('admin')) {
            return 'admin';
        }

        if ($this->hasRole('user')) {
            return 'user';
        }

        return null;
    }

    public function ensureRole(): void
    {
        if ($this->roles()->count() === 0) {
            $defaultRole = Role::where('name', 'user')->first();

            if ($defaultRole) {
                $this->roles()->attach($defaultRole->id);
            }
        }
    }

    public function creator()
    {
        return $this->hasMany(Data_stokkeluar::class, 'created_by');
    }

    public function poster()
    {
        return $this->hasMany(Data_stokkeluar::class, 'posted_by');
    }

    public function kartu()
    {
        return $this->hasMany(Data_kartustok::class, 'user_id');
    }

    public function dataDiri()
    {
        return $this->hasOne(Master_datadiri::class, 'user_id');
    }

    // Header
    public function getFotoProfileAttribute()
    {
        return $this->dataDiri && $this->dataDiri->foto_diri
            ? asset('foto_profile/' . $this->dataDiri->foto_diri)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->username);
    }

    public function readInfos()
    {
        return $this->belongsToMany(Master_info::class, 'info_user_reads')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function getMenusAttribute()
    {
        $roleNames = $this->roles->pluck('name')->toArray();

        return Menu::where('is_active', true)
            ->whereIn('prefix', $roleNames) // 🔥 FILTER BERDASARKAN ROLE
            ->orderBy('group_order')
            ->orderBy('order')
            ->get()
            ->groupBy('group')
            ->map(function ($groupMenus) {
                return $groupMenus->sortBy('order');
            });
    }

    // Pengguna
    public function getRoleBadgeAttribute()
    {
        if ($this->hasRole('admin')) {
            return '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase">Administrator</span>';
        }

        return '<span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase">Staff</span>';
    }
}
