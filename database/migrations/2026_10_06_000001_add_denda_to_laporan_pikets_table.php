<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pikets', function (Blueprint $table) {
            // Nominal denda dalam rupiah. 0 = tidak kena denda.
            $table->unsignedInteger('denda')->default(0)->after('menit_keterlambatan');
            // Apakah denda sudah dibayar?
            $table->boolean('denda_dibayar')->default(false)->after('denda');
            $table->timestamp('denda_dibayar_at')->nullable()->after('denda_dibayar');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pikets', function (Blueprint $table) {
            $table->dropColumn(['denda', 'denda_dibayar', 'denda_dibayar_at']);
        });
    }
};
