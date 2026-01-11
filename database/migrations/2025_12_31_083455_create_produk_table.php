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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('slug')->unique(); // URL ramah SEO
            $table->text('deskripsi');
            $table->integer('harga'); // Harga Asli
            $table->integer('stok')->default(10);
            $table->integer('terjual')->default(0);
            $table->string('foto')->nullable(); // Gambar Utama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
