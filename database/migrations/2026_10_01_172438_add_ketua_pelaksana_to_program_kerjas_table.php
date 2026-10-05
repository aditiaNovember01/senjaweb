<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_kerjas', function (Blueprint $table) {
            // Hapus kolom pengurus_id yang sebelumnya (jika ada)
            if (Schema::hasColumn('program_kerjas', 'pengurus_id')) {
                $table->dropForeign(['pengurus_id']);
                $table->dropColumn('pengurus_id');
            }

            // Tambah kolom ketua_pelaksana_id → relasi ke anggota
            $table->foreignId('ketua_pelaksana_id')
                ->nullable()
                ->after('divisi_id')
                ->constrained('anggotas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('program_kerjas', function (Blueprint $table) {
            $table->dropForeign(['ketua_pelaksana_id']);
            $table->dropColumn('ketua_pelaksana_id');
        });
    }
};
