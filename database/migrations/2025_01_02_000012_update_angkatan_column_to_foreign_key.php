<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });

        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('angkatan_id')
                ->nullable()
                ->after('divisi_id')
                ->constrained('angkatans')
                ->nullOnDelete();
        });

        Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });

        Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
            $table->foreignId('angkatan_id')
                ->nullable()
                ->after('divisi_id')
                ->constrained('angkatans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
            $table->year('angkatan')->after('divisi_id');
        });

        Schema::table('pendaftaran_anggotas', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn('angkatan_id');
            $table->year('angkatan')->after('divisi_id');
        });
    }
};
