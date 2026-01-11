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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();

            // Terhubung ke tabel Users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // --- INFO PENGIRIMAN ---
            $table->string('nama_penerima');    // Nama Penerima
            $table->string('hp_penerima');   // No HP Penerima
            $table->text('alamat_penerima');   // Alamat Lengkap

            // --- INFO KEUANGAN (PENTING!) ---
            $table->integer('subtotal');         // Harga total barang SEBELUM diskon & ongkir

            $table->string('kode_voucher')->nullable();      // Kode voucher yg dipakai (misal: ZIZI10)
            $table->integer('jumlah_diskon')->default(0); // Potongan harga dalam Rupiah (Snapshot)
            $table->integer('ongkir');

            $table->integer('total_akhir');      // Harga AKHIR yang harus dibayar user

            // --- STATUS & TRACKING ---
            $table->enum('status', ['Belum Dibayar', 'Menunggu Konfirmasi', 'Pembayaran Ditolak', 'Diproses', 'Dibayar', 'Dikirim', 'Selesai', 'Dibatalkan'])->default('Belum Dibayar');
            $table->string('resi')->nullable(); // No Resi JNE/J&T
            $table->boolean('sudah_dirating')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
