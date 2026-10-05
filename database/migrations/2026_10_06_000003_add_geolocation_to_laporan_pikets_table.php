<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pikets', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('catatan');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->unsignedInteger('jarak_meter')->nullable()->after('longitude')
                ->comment('Jarak dari sekretariat saat upload, dalam meter');
            $table->boolean('lokasi_valid')->default(true)->after('jarak_meter')
                ->comment('true = dalam radius yang diizinkan');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pikets', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'jarak_meter', 'lokasi_valid']);
        });
    }
};
