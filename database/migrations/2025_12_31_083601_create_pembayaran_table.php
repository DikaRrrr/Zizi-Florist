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
            $table->string('metode_pembayaran');
            $table->integer('jumlah');
            $table->string('bukti_bayar')->nullable(); 
            $table->string('nama_akun_bank')->nullable(); 
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
