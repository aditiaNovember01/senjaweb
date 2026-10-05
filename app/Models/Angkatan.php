<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Angkatan extends Model
{
    protected $fillable = ['nama', 'tahun', 'deskripsi'];

    /**
     * Label yang tampil di dropdown: "Angkatan Garuda (2022)"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->nama} ({$this->tahun})";
    }

    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }

    public function pendaftaranAnggotas(): HasMany
    {
        return $this->hasMany(PendaftaranAnggota::class);
    }
}
