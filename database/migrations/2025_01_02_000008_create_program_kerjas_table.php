<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->text('deskripsi');
            $table->foreignId('divisi_id')->constrained('divisis')->restrictOnDelete();
            $table->foreignId('periode_id')->constrained('periode_kepengurusans')->restrictOnDelete();
            $table->date('tanggal_mulai_estimasi');
            $table->date('tanggal_selesai_estimasi');
            $table->enum('status', ['Direncanakan', 'Sedang Berjalan', 'Selesai', 'Dibatalkan'])->default('Direncanakan');
            $table->unsignedInteger('target_peserta')->nullable();
            $table->decimal('estimasi_anggaran', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kerjas');
    }
};
