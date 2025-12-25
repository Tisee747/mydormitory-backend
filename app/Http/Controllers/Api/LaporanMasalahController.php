<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanMasalah;
use App\Models\Notifikasi;
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

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data' => $laporan
        ], 201);

        NotifikasiHelper::buat(
            1,
            'Laporan',
            'Laporan masalah baru dari penghuni'
        );

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

        return response()->json([
            'success' => true,
            'message' => 'Laporan diperbarui',
            'data' => $laporan
        ]);

        NotifikasiHelper::buat(
            $laporan->penghuni->user_id,
            'Laporan',
            'Laporan kamu sudah diselesaikan admin'
        );

    }
}
