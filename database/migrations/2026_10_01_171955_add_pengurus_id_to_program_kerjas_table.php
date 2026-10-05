<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_kerjas', function (Blueprint $table) {
            $table->foreignId('pengurus_id')
                ->nullable()
                ->after('divisi_id')
                ->constrained('pengurus')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('program_kerjas', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Pengurus::class);
            $table->dropColumn('pengurus_id');
        });
    }
};
