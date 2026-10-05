<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pikets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tugas', 150);              // e.g. "Piket Sekretariat Minggu ke-1"
            $table->date('tanggal_piket');                  // tanggal wajib piket
            $table->time('jam_mulai')->default('07:00:00'); // batas mulai
            $table->time('batas_upload');                   // jam deadline upload bukti
            $table->foreignId('divisi_id')
                ->nullable()
                ->constrained('divisis')
                ->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pikets');
    }
};
