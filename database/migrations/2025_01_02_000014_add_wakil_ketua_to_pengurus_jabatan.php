<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: ubah enum dengan menambahkan 'Wakil Ketua' setelah 'Ketua'
        DB::statement("ALTER TABLE pengurus MODIFY jabatan ENUM(
            'Pembina',
            'Ketua',
            'Wakil Ketua',
            'Steering Committee',
            'Sekretaris',
            'Bendahara',
            'Humas',
            'Kadiv Musik',
            'Kadiv Tari',
            'Kadiv Seni Rupa',
            'Kadiv Teater'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengurus MODIFY jabatan ENUM(
            'Pembina',
            'Ketua',
            'Steering Committee',
            'Sekretaris',
            'Bendahara',
            'Humas',
            'Kadiv Musik',
            'Kadiv Tari',
            'Kadiv Seni Rupa',
            'Kadiv Teater'
        ) NOT NULL");
    }
};
