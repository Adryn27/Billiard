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
        Schema::create('meja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->string('nomor_meja')->unique();
            $table->enum('status',['0','1','2'])->default('0'); //Tersedia, Digunakan, Direservasi
            $table->timestamps();
            $table->foreign('kategori_id')->references('id')->on('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meja');
    }
};
