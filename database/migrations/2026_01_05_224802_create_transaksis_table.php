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
    Schema::create('transaksis', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_transaksi')->unique(); // Kode unik: TRX-001
        $table->foreignId('cabang_id')->constrained('cabangs');
        $table->foreignId('user_id')->constrained('users'); // Siapa kasirnya
        $table->bigInteger('total_belanja');
        $table->bigInteger('bayar');
        $table->bigInteger('kembalian');
        $table->timestamps(); // Mencatat tanggal transaksi
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
