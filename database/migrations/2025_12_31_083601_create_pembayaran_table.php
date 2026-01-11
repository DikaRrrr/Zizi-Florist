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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');

            $table->string('metode_pembayaran'); // 'transfer_bca', 'transfer_mandiri', 'midtrans', etc
            $table->integer('jumlah');

            // Khusus Transfer Manual
            $table->string('bukti_bayar')->nullable(); // Foto struk
            $table->string('nama_akun_bank')->nullable(); // Nama pengirim

            $table->enum('status', ['diproses', 'berhasil', 'gagal'])->default('diproses');
            $table->timestamp('waktu_bayar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
