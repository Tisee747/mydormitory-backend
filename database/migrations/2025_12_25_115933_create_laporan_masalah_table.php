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
        Schema::create('laporan_masalah', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('penghuni_id');
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->string('tipe_masalah');
            $table->text('deskripsi');

            $table->string('prioritas')->default('Sedang');
            $table->string('status')->default('Baru');

            $table->text('catatan_admin')->nullable();

            $table->timestamp('tgl_lapor')->useCurrent();
            $table->timestamp('tgl_selesai')->nullable();

            $table->timestamps();

            $table->foreign('penghuni_id')->references('id')->on('penghunis')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_masalah');
    }
};
