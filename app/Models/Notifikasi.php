<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'id_penerima',
        'jenis_notifikasi',
        'isi_pesan',
        'status_baca',
        'waktu_kirim'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }
}
