<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 50)->unique();
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->string('nama_pengirim', 100);
            $table->string('perihal', 255);
            $table->foreignId('kategori_id')->constrained('kategori_surats')->restrictOnDelete();
            $table->string('berkas_pdf')->nullable(); // path relatif storage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
