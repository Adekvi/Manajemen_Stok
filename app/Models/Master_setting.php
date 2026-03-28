<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master_setting extends Model
{
    protected $fillable = [
        'id',
        'nama_toko',
        'email',
        'no_telepon',
        'alamat',
        'logo',
        'rekening_bank',
        'qris',
        'bank_active',
        'qris_active',
        'cash_active',
        'jam_operasional'
    ];

    protected $casts = [
        'rekening_bank' => 'array',
        'jam_operasional' => 'array',
    ];
}
