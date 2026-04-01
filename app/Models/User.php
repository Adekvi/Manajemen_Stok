<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_online'         => 'boolean',
            'is_active'         => 'boolean',
        ];
    }

    // LOGIN AUTH
    public function isActive(): bool
    {
        return (bool) $this->is_active;   // pakai (bool) agar lebih aman
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
}
