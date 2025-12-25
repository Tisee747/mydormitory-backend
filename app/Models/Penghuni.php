<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'angkatan',
        'gedung',
        'nomor_kamar',
        'nomor_hp',
        'qr_data',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
