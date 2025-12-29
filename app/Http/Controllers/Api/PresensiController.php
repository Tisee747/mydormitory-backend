<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Penghuni;
use App\Helpers\NotifikasiHelper;

class PresensiController extends Controller
{
    // lakukanPresensi(qr_data)
        public function store(Request $request){
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'qr_code' => 'required|string',
            'lokasi_lat' => 'nullable|numeric',
            'lokasi_lng' => 'nullable|numeric',
        ]);

        // VALIDASI QR STATIS (1 QR SAJA)
        $validQr = array_map('trim', explode(',', env('VALID_QR_CODES', 'DORM-A')));
        if (!in_array($request->qr_code, $validQr, true)) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak valid'
            ], 422);
        }

        // (OPSIONAL) Cegah presensi dobel di hari yang sama
        $sudahPresensi = Presensi::where('penghuni_id', $request->penghuni_id)
            ->whereDate('waktu_presensi', now()->toDateString())
            ->exists();

        if ($sudahPresensi) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi hari ini sudah tercatat'
            ], 409);
        }

        // SIMPAN PRESENSI
        $presensi = Presensi::create([
            'penghuni_id' => $request->penghuni_id,
            'waktu_presensi' => now(),
            'qr_code' => $request->qr_code,
            'lokasi_lat' => $request->lokasi_lat,
            'lokasi_lng' => $request->lokasi_lng,
        ]);

        // NOTIFIKASI ADMIN (opsional)
        NotifikasiHelper::buat(
            (int) env('ADMIN_USER_ID', 1),
            'Presensi',
            'Presensi baru berhasil dicatat'
        );

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil',
            'data' => $presensi
        ], 201);
    }


    // lihatRiwayatPresensi()
    public function riwayat($penghuni_id){
        return Presensi::where('penghuni_id', $penghuni_id)
            ->orderBy('waktu_presensi', 'desc')
            ->get();
    }

    // updateDataPresensi()
    public function update(Request $request, $id){
        $presensi = Presensi::findOrFail($id);

        $presensi->update([
            'waktu_presensi' => $request->waktu_presensi ?? $presensi->waktu_presensi,
            'status_verifikasi' => $request->status_verifikasi ?? $presensi->status_verifikasi,
            'catatan_admin' => $request->catatan_admin ?? $presensi->catatan_admin,
            'id_admin_verifikator' => $request->id_admin_verifikator ?? $presensi->id_admin_verifikator,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data presensi diperbarui',
            'data' => $presensi
        ]);
    }

    public function verifikasi(Request $request, $id){
        $presensi = Presensi::findOrFail($id);

        $presensi->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_admin' => $request->catatan_admin,
            'id_admin_verifikator' => $request->admin_id,
        ]);

        // Notifikasi ke penghuni setelah diverifikasi
        NotifikasiHelper::buat(
            $presensi->penghuni->user_id,
            'Presensi',
            'Presensi kamu telah diverifikasi'
        );

        return response()->json([
            'success' => true,
            'message' => 'Presensi diverifikasi',
            'data' => $presensi
        ]);
    }
}
