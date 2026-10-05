<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_websites', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('path_foto');       // path relatif dari public/
            $table->string('sumber')->default('upload'); // 'upload' atau 'assets'
            $table->string('keterangan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_websites');
    }
};
