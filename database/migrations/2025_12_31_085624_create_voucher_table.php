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
        Schema::create('voucher', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Kode Voucher (misal: ZIZIHEMAT)
            $table->enum('tipe', ['percent', 'fixed']); // Jenis: % atau Potongan Harga Tetap
            $table->integer('nilai'); // Nilainya (misal: 10 untuk 10%, atau 5000 untuk Rp 5.000)

            // Batasan (Optional tapi penting)
            $table->integer('minimal_pembelian')->default(0); // Minimal belanja Rp berapa baru bisa pakai
            $table->date('tanggal_mulai')->nullable(); // Berlaku mulai kapan
            $table->date('tanggal_selesai')->nullable();   // Kadaluarsa kapan

            $table->boolean('is_active')->default(true); // Bisa dimatikan manual oleh admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};
