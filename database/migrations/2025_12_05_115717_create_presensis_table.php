<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(){
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penghuni_id');

            $table->dateTime('waktu_presensi');
            $table->double('lokasi_lat')->nullable();
            $table->double('lokasi_lng')->nullable();

            $table->string('qr_code')->nullable();
            $table->string('status_verifikasi')->default('Belum Diverifikasi');
            $table->string('catatan_admin')->nullable();
            $table->unsignedBigInteger('id_admin_verifikator')->nullable();

            $table->timestamps();

            $table->foreign('penghuni_id')->references('id')->on('penghunis')->onDelete('cascade');
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
