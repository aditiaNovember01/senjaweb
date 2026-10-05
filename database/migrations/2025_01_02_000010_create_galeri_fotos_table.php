<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->string('path_foto'); // path relatif storage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_fotos');
    }
};
