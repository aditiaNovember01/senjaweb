<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_kepengurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 20); // contoh: 2024/2025
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_kepengurusans');
    }
};
