<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PenghuniController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\LaporanMasalahController;
use App\Http\Controllers\Api\NotifikasiController;

// AUTH
Route::post('/login', [AuthController::class, 'login']);

// (opsional) ping test
Route::get('/dorms', function () {
    return response()->json(['message' => 'Sukses! Rute /api/dorms terhubung.']);
});

// PENGHUNI
Route::get('/penghuni', [PenghuniController::class, 'index']);
Route::post('/penghuni', [PenghuniController::class, 'store']);
Route::get('/penghuni/{id}', [PenghuniController::class, 'show']);
Route::put('/penghuni/{id}', [PenghuniController::class, 'update']);
Route::delete('/penghuni/{id}', [PenghuniController::class, 'destroy']);

// PRESENSI
Route::post('/presensi', [PresensiController::class, 'store']);
Route::get('/presensi/riwayat/{penghuni_id}', [PresensiController::class, 'riwayat']);
Route::put('/presensi/{id}', [PresensiController::class, 'update']);
Route::put('/presensi/verifikasi/{id}', [PresensiController::class, 'verifikasi']);

// LAPORAN MASALAH
Route::post('/laporan', [LaporanMasalahController::class, 'store']);
Route::get('/laporan', [LaporanMasalahController::class, 'index']);
Route::put('/laporan/{id}', [LaporanMasalahController::class, 'update']);

// NOTIFIKASI
Route::get('/notifikasi/{user_id}', [NotifikasiController::class, 'index']);
Route::put('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca']);
