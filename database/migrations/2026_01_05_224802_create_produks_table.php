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
    Schema::create('produks', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('kategori'); // Makanan / Minuman
        $table->bigInteger('harga'); // Menggunakan BigInteger untuk Rupiah
        $table->integer('stok')->default(0); 
        $table->boolean('is_active')->default(true);
        $table->string('gambar')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
