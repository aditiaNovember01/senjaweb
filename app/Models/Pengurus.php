<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengurus extends Model
{
    protected $table = 'pengurus';

    protected $fillable = [
        'anggota_id',
        'periode_id',
        'jabatan',
        'urutan',
    ];

    /**
     * Urutan tampil sesuai hierarki jabatan.
     */
    public static array $jabatanOptions = [
        'Pembina'             => 'Pembina',
        'Ketua'               => 'Ketua',
        'Wakil Ketua'         => 'Wakil Ketua',
        'Steering Committee'  => 'Steering Committee',
        'Sekretaris'          => 'Sekretaris',
        'Bendahara'           => 'Bendahara',
        'Humas'               => 'Humas',
        'Kadiv Musik'         => 'Kadiv Musik',
        'Kadiv Tari'          => 'Kadiv Tari',
        'Kadiv Seni Rupa'     => 'Kadiv Seni Rupa',
        'Kadiv Teater'        => 'Kadiv Teater',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }
}
