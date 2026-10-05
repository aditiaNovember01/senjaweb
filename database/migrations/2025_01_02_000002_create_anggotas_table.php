<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('nim')->unique();
            $table->string('email')->unique();
            $table->string('nomor_telepon', 20);
            $table->foreignId('divisi_id')->constrained('divisis')->restrictOnDelete();
            $table->year('angkatan');
            $table->date('tanggal_bergabung');
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Alumni'])->default('Aktif');
            $table->string('foto_profil')->nullable(); // path relatif storage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
