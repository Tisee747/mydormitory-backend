<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class PenghuniController extends Controller
{
    public function index()
    {
        return Penghuni::all();
    }

    public function store(Request $request)
    {
        // Mode A (buat user+penghuni) atau mode lama (pakai user_id)
        $validated = $request->validate([
            // kalau ada user_id, email/password tidak wajib
            'user_id' => 'nullable|exists:users,id|required_without:email',

            // kalau tidak ada user_id, wajib bikin akun
            'email' => 'required_without:user_id|email|unique:users,email',
            'password' => 'required_without:user_id|string|min:6',

            // data penghuni
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:32|unique:penghunis,nim',
            'angkatan' => 'required|string|max:10',
            'gedung' => 'required|string|max:50',
            'nomor_kamar' => 'required|integer',
            'nomor_hp' => 'nullable|string|max:30',
            'qr_data' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        // Kalau belum ada qr_data, generate sederhana
        if (empty($validated['qr_data'])) {
            $validated['qr_data'] = 'QR-' . $validated['nim'] . '-' . now()->format('YmdHis');
        }

        return DB::transaction(function () use ($validated) {
            $user = null;

            // MODE A: kalau user_id tidak dikirim, buat user baru
            if (empty($validated['user_id'])) {
                $user = User::create([
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
                $validated['user_id'] = $user->id;
            } else {
                $user = User::find($validated['user_id']);
            }

            // jangan ikutkan email/password ke tabel penghunis
            unset($validated['email'], $validated['password']);

            $penghuni = Penghuni::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Penghuni berhasil ditambahkan',
                'user' => $user,
                'data' => $penghuni
            ], 201);
        });
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
