<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanMasalah;
use App\Helpers\NotifikasiHelper;

class LaporanMasalahController extends Controller
{
    // Penghuni buat laporan
    public function store(Request $request)
    {
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'tipe_masalah' => 'required',
            'deskripsi' => 'required',
        ]);

        $laporan = LaporanMasalah::create([
            'penghuni_id' => $request->penghuni_id,
            'tipe_masalah' => $request->tipe_masalah,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas ?? 'Sedang',
        ]);

        // Notifikasi ke admin (pakai ADMIN_USER_ID dari .env, default 1)
        NotifikasiHelper::buat(
            (int) env('ADMIN_USER_ID', 1),
            'Laporan',
            'Laporan masalah baru dari penghuni'
        );

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data' => $laporan
        ], 201);
    }

    // Admin lihat semua laporan
    public function index()
    {
        return LaporanMasalah::all();
    }

    // Admin update laporan
    public function update(Request $request, $id)
    {
        $laporan = LaporanMasalah::findOrFail($id);

        $laporan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'admin_id' => $request->admin_id,
            'tgl_selesai' => $request->status === 'Selesai' ? now() : null
        ]);

        // Notifikasi ke penghuni saat status berubah
        if ($laporan->penghuni && $laporan->penghuni->user_id) {
            $pesan = $request->status === 'Selesai'
                ? 'Laporan kamu sudah diselesaikan admin'
                : 'Laporan kamu sedang diproses oleh admin';

            NotifikasiHelper::buat(
                $laporan->penghuni->user_id,
                'Laporan',
                $pesan
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan diperbarui',
            'data' => $laporan
        ]);
    }
}
