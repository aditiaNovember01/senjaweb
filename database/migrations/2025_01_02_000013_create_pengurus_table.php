<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periode_kepengurusans')->cascadeOnDelete();
            $table->enum('jabatan', [
                'Pembina',
                'Ketua',
                'Steering Committee',
                'Sekretaris',
                'Bendahara',
                'Humas',
                'Kadiv Musik',
                'Kadiv Tari',
                'Kadiv Seni Rupa',
                'Kadiv Teater',
            ]);
            $table->unsignedTinyInteger('urutan')->default(0); // untuk sorting tampilan
            $table->timestamps();

            // Satu anggota hanya bisa punya satu jabatan per periode
            $table->unique(['anggota_id', 'periode_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengurus');
    }
};
