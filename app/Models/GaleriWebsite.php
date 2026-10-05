<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriWebsite extends Model
{
    protected $fillable = [
        'judul',
        'path_foto',
        'sumber',
        'keterangan',
        'is_aktif',
        'is_beranda',
        'tag_beranda',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif'   => 'boolean',
            'is_beranda' => 'boolean',
        ];
    }

    /**
     * URL publik foto — support sumber assets/ (dari public/) maupun storage/
     */
    public function getUrlFotoAttribute(): string
    {
        if ($this->sumber === 'assets') {
            return asset($this->path_foto);
        }
        return asset('storage/' . $this->path_foto);
    }

    /**
     * Ambil semua foto aktif, diurutkan
     */
    public static function aktif()
    {
        return static::where('is_aktif', true)
            ->orderBy('urutan')
            ->orderBy('created_at')
            ->get();
    }
}
