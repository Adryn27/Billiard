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
        Schema::create('waitinglist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('pelanggan_id');
            $table->datetime('jam_daftar');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitinglist');
    }
};
