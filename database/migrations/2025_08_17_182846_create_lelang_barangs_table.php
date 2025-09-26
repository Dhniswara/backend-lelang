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
        Schema::create('lelang_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('gambar_barang');
            $table->string('nama_barang');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->text('deskripsi');
            $table->unsignedBigInteger('harga_awal');
            $table->unsignedBigInteger('harga_akhir')->nullable();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lelang_barangs');
    }
};
