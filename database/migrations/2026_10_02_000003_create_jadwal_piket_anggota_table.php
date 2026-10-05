<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot: many-to-many between jadwal_pikets and anggotas.
     * Stores which anggota are assigned to a piket schedule.
     */
    public function up(): void
    {
        Schema::create('jadwal_piket_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_piket_id')
                ->constrained('jadwal_pikets')
                ->cascadeOnDelete();
            $table->foreignId('anggota_id')
                ->constrained('anggotas')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['jadwal_piket_id', 'anggota_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_piket_anggota');
    }
};
