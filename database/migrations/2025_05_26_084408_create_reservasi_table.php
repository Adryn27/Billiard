<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('meja_id');
            $table->unsignedBigInteger('pelanggan_id');
            $table->datetime('jam_mulai');
            $table->datetime('jam_berakhir');
            $table->enum('proses',['0','1','2'])->default('0'); //Pending, On-Going, Completed
            $table->double('total');
            $table->enum('metode_bayar',['0','1','2'])->nullable(); //Cash, Bank, E-Wallet
            $table->enum('status_bayar',['0','1'])->default('0'); //Belum Dibayar, Dibayar
            $table->timestamps();
            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('meja_id')->references('id')->on('meja');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
