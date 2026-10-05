<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 50)->unique();
            $table->date('tanggal_surat');
            $table->date('tanggal_dikirim');
            $table->string('nama_penerima', 100);
            $table->string('perihal', 255);
            $table->foreignId('kategori_id')->constrained('kategori_surats')->restrictOnDelete();
            $table->string('berkas_pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
