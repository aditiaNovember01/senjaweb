<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            // Untuk status Pembina: divisi, angkatan, tanggal_bergabung tidak wajib
            $table->foreignId('divisi_id')->nullable()->change();
            $table->date('tanggal_bergabung')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('anggotas', function (Blueprint $table) {
            $table->foreignId('divisi_id')->nullable(false)->change();
            $table->date('tanggal_bergabung')->nullable(false)->change();
        });
    }
};
