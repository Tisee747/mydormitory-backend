<?php

namespace App\Helpers;

use App\Models\Notifikasi;

class NotifikasiHelper
{
    public static function buat($id_penerima, $jenis, $pesan)
    {
        return Notifikasi::create([
            'id_penerima' => $id_penerima,
            'jenis_notifikasi' => $jenis,
            'isi_pesan' => $pesan,
            'status_baca' => false,
            'waktu_kirim' => now()
        ]);
    }
}
