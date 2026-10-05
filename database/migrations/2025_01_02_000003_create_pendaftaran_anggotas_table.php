<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('nim')->unique();
            $table->string('email')->unique();
            $table->string('nomor_telepon', 20);
            $table->foreignId('divisi_id')->constrained('divisis')->restrictOnDelete();
            $table->year('angkatan');
            $table->enum('status', ['Menunggu Verifikasi'])->default('Menunggu Verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_anggotas');
    }
};
