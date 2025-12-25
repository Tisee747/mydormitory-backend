<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    /**
     * tampilkanNotifikasi(id_penerima)
     */
    public function index($user_id)
    {
        return response()->json(
            Notifikasi::where('id_penerima', $user_id)
                ->where('status_baca', false)
                ->orderBy('waktu_kirim', 'desc')
                ->get()
        );
    }

    /**
     * tandaiDibaca(id_notifikasi)
     */
    public function baca($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['status_baca' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca'
        ]);
    }
}
