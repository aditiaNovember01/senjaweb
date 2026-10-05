<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    protected $fillable = ['nama', 'deskripsi'];

    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }

    public function pendaftaranAnggotas(): HasMany
    {
        return $this->hasMany(PendaftaranAnggota::class);
    }

    public function programKerjas(): HasMany
    {
        return $this->hasMany(ProgramKerja::class);
    }

    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}
