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
            $table->string('kode')->unique(); 
            $table->enum('tipe', ['percent', 'fixed']); 
            $table->integer('nilai'); 
            $table->integer('minimal_pembelian')->default(0); 
            $table->date('tanggal_mulai')->nullable(); 
            $table->date('tanggal_selesai')->nullable();   
            $table->boolean('is_active')->default(true); 
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
