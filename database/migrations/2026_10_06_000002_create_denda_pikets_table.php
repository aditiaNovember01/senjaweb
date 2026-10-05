<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel denda piket — dibuat satu baris per anggota per tanggal piket
     * yang tidak hadir (tidak upload bukti setelah deadline lewat).
     * Juga mencatat denda dari laporan terlambat.
     */
    public function up(): void
    {
        Schema::create('denda_pikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_piket_id')
                ->constrained('jadwal_pikets')->cascadeOnDelete();
            $table->foreignId('anggota_id')
                ->constrained('anggotas')->cascadeOnDelete();
            $table->date('tanggal_piket');
            $table->enum('alasan', ['Tidak Hadir', 'Terlambat']);
            $table->unsignedInteger('nominal')->default(5000); // Rp5.000
            $table->boolean('sudah_dibayar')->default(false);
            $table->timestamp('dibayar_at')->nullable();
            $table->timestamps();

            // Satu denda per anggota per tanggal
            $table->unique(['anggota_id', 'tanggal_piket']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denda_pikets');
    }
};
