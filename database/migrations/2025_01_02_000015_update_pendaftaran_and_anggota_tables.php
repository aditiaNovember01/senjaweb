<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update tabel anggotas: ubah status enum
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('anggotas', function (Blueprint $table) {
            $table->enum('status', [
                'Pembina',
                'Anggota Aktif',
                'Anggota Pasif',
                'Anggota Kehormatan',
            ])->default('Anggota Aktif')->after('tanggal_bergabung');
        });

        // Update tabel pendaftaran: hapus angkatan_id, tambah prestasi & berkas
        Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
            $table->text('prestasi')->nullable()->after('nomor_telepon');
            $table->string('berkas')->nullable()->after('prestasi'); // path PDF/JPG
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('anggotas', function (Blueprint $table) {
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Alumni'])->default('Aktif')->after('tanggal_bergabung');
        });

        Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
            $table->dropColumn(['prestasi', 'berkas']);
            $table->foreignId('angkatan_id')->nullable()->constrained('angkatans')->nullOnDelete();
        });
    }
};
