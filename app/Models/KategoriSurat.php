<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSurat extends Model
{
    protected $fillable = ['nama'];

    public function suratMasuks(): HasMany
    {
        return $this->hasMany(SuratMasuk::class, 'kategori_id');
    }

    public function suratKeluars(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'kategori_id');
    }
}
