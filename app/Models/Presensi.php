<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $fillable = [
    'penghuni_id',
    'waktu_presensi',
    'qr_code',
    'lokasi_lat',
    'lokasi_lng',
    'status_verifikasi',
    'catatan_admin',
    'id_admin_verifikator',
    ];


    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
}
