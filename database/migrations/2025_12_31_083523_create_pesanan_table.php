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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_penerima'); 
            $table->string('hp_penerima'); 
            $table->text('alamat_penerima');  
            $table->integer('subtotal');        
            $table->string('kode_voucher')->nullable();      
            $table->integer('jumlah_diskon')->default(0); 
            $table->integer('ongkir');
            $table->integer('total_akhir');      
            $table->enum('status', ['Belum Dibayar', 'Menunggu Konfirmasi', 'Pembayaran Ditolak', 'Diproses', 'Dibayar', 'Dikirim', 'Selesai', 'Dibatalkan'])->default('Belum Dibayar');
            $table->string('resi')->nullable(); 
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
