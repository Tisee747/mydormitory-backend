<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            // relasi ke User (Admin / Penghuni)
            $table->unsignedBigInteger('id_penerima');

            $table->string('jenis_notifikasi');
            $table->text('isi_pesan');
            $table->boolean('status_baca')->default(false);
            $table->dateTime('waktu_kirim');

            $table->timestamps();

            $table->foreign('id_penerima')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasis');
    }
};
