<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMasalah extends Model
{
    protected $table = 'laporan_masalah';

    protected $fillable = [
        'penghuni_id',
        'admin_id',
        'tipe_masalah',
        'deskripsi',
        'prioritas',
        'status',
        'catatan_admin',
        'tgl_lapor',
        'tgl_selesai'
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
}
