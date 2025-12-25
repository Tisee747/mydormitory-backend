<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use App\Helpers\NotifikasiHelper;

class PenghuniController extends Controller
{
    public function index()
    {
        return Penghuni::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'penghuni_id' => 'required|exists:penghunis,id',
            'qr_code' => 'required'
        ]);

        $presensi = Presensi::create([
            'penghuni_id' => $request->penghuni_id,
            'waktu_presensi' => now(),
            'qr_code' => $request->qr_code,
            'status_verifikasi' => 'Belum Diverifikasi'
        ]);

        $penghuni = Penghuni::find($request->penghuni_id);

        NotifikasiHelper::buat(
            $penghuni->user_id,
            'Presensi',
            'Presensi berhasil dicatat dan menunggu verifikasi admin'
        );

        return response()->json([
            'success' => true,
            'data' => $presensi
        ]);
    }

    public function show($id)
    {
        return Penghuni::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $data = Penghuni::findOrFail($id);
        $data->update($request->all());
        return response()->json($data);
    }

    public function destroy($id)
    {
        Penghuni::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
