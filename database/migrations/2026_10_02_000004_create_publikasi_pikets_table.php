<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasi_pikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_piket_id')
                ->constrained('jadwal_pikets')
                ->cascadeOnDelete();
            $table->foreignId('anggota_id')
                ->constrained('anggotas')
                ->cascadeOnDelete();

            $table->string('foto_bukti');          // path foto bukti piket
            $table->text('catatan')->nullable();   // catatan/keterangan dari anggota
            $table->timestamp('uploaded_at');      // waktu upload aktual

            // Status keterlambatan (dihitung saat upload)
            $table->enum('status_kehadiran', ['Tepat Waktu', 'Terlambat', 'Tidak Hadir'])
                ->default('Tepat Waktu');
            $table->unsignedSmallInteger('menit_keterlambatan')->default(0);

            $table->timestamps();

            // Satu anggota hanya bisa submit satu publikasi per jadwal
            $table->unique(['jadwal_piket_id', 'anggota_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi_pikets');
    }
};
