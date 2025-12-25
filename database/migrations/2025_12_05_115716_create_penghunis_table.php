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
        Schema::create('penghunis', function (Blueprint $table) {
            $table->id();

            // relasi ke User (superclass)
            $table->unsignedBigInteger('user_id');

            // atribut sesuai SKPL
            $table->string('nama');
            $table->string('nim')->unique();
            $table->string('angkatan');
            $table->string('gedung');
            $table->integer('nomor_kamar');

            // atribut tambahan
            $table->string('nomor_hp')->nullable();
            $table->string('qr_data')->nullable();
            $table->string('status')->default('aktif');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghunis');
    }
};
