<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    public function index()
    {
        return Penghuni::all();
    }

    public function store(Request $request)
    {
        // Membuat data penghuni asrama (FR-006)
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:32|unique:penghunis,nim',
            'angkatan' => 'required|string|max:10',
            'gedung' => 'required|string|max:50',
            'nomor_kamar' => 'required|integer',
            'nomor_hp' => 'nullable|string|max:30',
            'qr_data' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif'
        ]);

        // Kalau belum ada qr_data, generate sederhana (bisa diganti nanti)
        if (empty($validated['qr_data'])) {
            $validated['qr_data'] = 'QR-' . $validated['nim'] . '-' . now()->format('YmdHis');
        }

        $penghuni = Penghuni::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil ditambahkan',
            'data' => $penghuni
        ], 201);
    }

    public function show($id)
    {
        return Penghuni::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $penghuni = Penghuni::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'nama' => 'sometimes|string|max:255',
            'nim' => 'sometimes|string|max:32|unique:penghunis,nim,' . $penghuni->id,
            'angkatan' => 'sometimes|string|max:10',
            'gedung' => 'sometimes|string|max:50',
            'nomor_kamar' => 'sometimes|integer',
            'nomor_hp' => 'nullable|string|max:30',
            'qr_data' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif'
        ]);

        $penghuni->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil diperbarui',
            'data' => $penghuni
        ]);
    }

    public function destroy($id)
    {
        Penghuni::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
