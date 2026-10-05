<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->text('deskripsi');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('lokasi', 255);
            $table->foreignId('divisi_id')->constrained('divisis')->restrictOnDelete();
            $table->enum('status', ['Akan Datang', 'Sedang Berlangsung', 'Selesai', 'Dibatalkan'])->default('Akan Datang');
            $table->string('gambar_poster')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
