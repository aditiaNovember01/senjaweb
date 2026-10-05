<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Redesign piket:
 * - jadwal_pikets: berbasis hari dalam seminggu (0=Minggu … 6=Sabtu), bukan tanggal spesifik
 * - jadwal_piket_anggota: pivot unchanged (anggota ditugaskan per jadwal hari)
 * - laporan_pikets: laporan per anggota per tanggal aktual (PK: anggota_id + tanggal_laporan)
 *   — menggantikan publikasi_pikets
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel lama (urutan: child dulu)
        Schema::dropIfExists('publikasi_pikets');
        Schema::dropIfExists('jadwal_piket_anggota');
        Schema::dropIfExists('jadwal_pikets');

        // 2. Buat jadwal_pikets baru — berbasis hari
        Schema::create('jadwal_pikets', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('hari_ke')
                ->comment('0=Minggu,1=Senin,2=Selasa,3=Rabu,4=Kamis,5=Jumat,6=Sabtu');
            $table->time('jam_mulai')->default('07:00:00');
            $table->time('batas_upload')->default('10:00:00')
                ->comment('Upload setelah jam ini dihitung terlambat');
            $table->text('keterangan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            // Satu entri per hari (tidak boleh duplikat hari)
            $table->unique('hari_ke');
        });

        // 3. Pivot anggota → jadwal hari
        Schema::create('jadwal_piket_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_piket_id')
                ->constrained('jadwal_pikets')->cascadeOnDelete();
            $table->foreignId('anggota_id')
                ->constrained('anggotas')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['jadwal_piket_id', 'anggota_id']);
        });

        // 4. Laporan piket — satu baris per anggota per hari aktual
        Schema::create('laporan_pikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_piket_id')
                ->constrained('jadwal_pikets')->cascadeOnDelete();
            $table->foreignId('anggota_id')
                ->constrained('anggotas')->cascadeOnDelete();
            $table->date('tanggal_laporan');          // tanggal aktual hari upload
            $table->string('foto_bukti');             // path foto
            $table->text('catatan')->nullable();
            $table->timestamp('uploaded_at');         // jam aktual upload
            $table->enum('status_kehadiran', ['Tepat Waktu', 'Terlambat', 'Tidak Hadir'])
                ->default('Tepat Waktu');
            $table->unsignedSmallInteger('menit_keterlambatan')->default(0);
            $table->timestamps();

            // PK logis: satu anggota hanya bisa laporan satu kali per tanggal
            $table->unique(['anggota_id', 'tanggal_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pikets');
        Schema::dropIfExists('jadwal_piket_anggota');
        Schema::dropIfExists('jadwal_pikets');
    }
};
