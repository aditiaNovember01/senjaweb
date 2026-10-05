<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeri_websites', function (Blueprint $table) {
            $table->boolean('is_beranda')->default(false)->after('is_aktif');
            $table->string('tag_beranda', 100)->nullable()->after('is_beranda');
        });
    }

    public function down(): void
    {
        Schema::table('galeri_websites', function (Blueprint $table) {
            $table->dropColumn(['is_beranda', 'tag_beranda']);
        });
    }
};
