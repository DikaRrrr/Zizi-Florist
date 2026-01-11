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
        Schema::create('akun_bank', function (Blueprint $table) {
            $table->id();
            $table->string('bank');      // Nama Bank (Contoh: BCA)
            $table->string('no_rekening'); // Nomor Rekening (Contoh: 1234567890)
            $table->string('atas_nama'); // Atas Nama (Contoh: Zizi Florist)
            $table->boolean('is_active')->default(true); // Biar bisa disembunyikan kalau rekening mati
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_bank');
    }
};
