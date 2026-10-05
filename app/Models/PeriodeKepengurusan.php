<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeKepengurusan extends Model
{
    protected $fillable = ['nama', 'is_aktif'];

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
        ];
    }

    public function programKerjas(): HasMany
    {
        return $this->hasMany(ProgramKerja::class, 'periode_id');
    }

    public function pengurus(): HasMany
    {
        return $this->hasMany(Pengurus::class, 'periode_id');
    }
}
