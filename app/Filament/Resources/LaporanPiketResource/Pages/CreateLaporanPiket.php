<?php

namespace App\Filament\Resources\LaporanPiketResource\Pages;

use App\Filament\Resources\LaporanPiketResource;
use App\Models\LaporanPiket;
use App\Models\SiteSetting;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;

class CreateLaporanPiket extends CreateRecord
{
    protected static string $resource = LaporanPiketResource::class;

    protected static string $view = 'filament.pages.create-laporan-piket';

    // ── Geo koordinat disimpan di property Livewire ────────────────────────────
    public ?string $geoLat = null;
    public ?string $geoLng = null;

    /**
     * Listener untuk event dari Alpine JS saat GPS berhasil didapat.
     */
    #[On('geo-update')]
    public function onGeoUpdate(float $latitude, float $longitude): void
    {
        $this->geoLat = (string) $latitude;
        $this->geoLng = (string) $longitude;
    }

    // ── Sebelum simpan: inject geo + timestamp + validasi ─────────────────────

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tz = config('app.timezone', 'Asia/Jakarta');

        // 1. Timestamp WIB — datetime penuh
        $nowLocal = now($tz);
        $data['uploaded_at']     = $nowLocal->toDateTimeString();
        $data['tanggal_laporan'] = $nowLocal->toDateString();

        // 2. Ambil koordinat dari property Livewire (lebih reliable dari form hidden)
        $lat = filled($this->geoLat) ? $this->geoLat : ($data['latitude']  ?? null);
        $lng = filled($this->geoLng) ? $this->geoLng : ($data['longitude'] ?? null);

        $sekLat    = (float) SiteSetting::get('piket_lat', '0');
        $sekLng    = (float) SiteSetting::get('piket_lng', '0');
        $radius    = (int)   SiteSetting::get('piket_radius_meter', '50');
        $geoActive = $sekLat != 0.0 && $sekLng != 0.0;

        if (filled($lat) && filled($lng)) {
            $data['latitude']  = (float) $lat;
            $data['longitude'] = (float) $lng;

            if ($geoActive) {
                $jarak = LaporanPiket::hitungJarak(
                    (float) $lat, (float) $lng,
                    $sekLat, $sekLng
                );
                $data['jarak_meter']  = $jarak;
                $data['lokasi_valid'] = $jarak <= $radius;

                if (! $data['lokasi_valid']) {
                    throw ValidationException::withMessages([
                        'data.latitude' => "Lokasi kamu terlalu jauh dari sekretariat ({$jarak} meter). Maksimal {$radius} meter.",
                    ]);
                }
            } else {
                $data['jarak_meter']  = null;
                $data['lokasi_valid'] = true;
            }
        } else {
            // GPS tidak ada / ditolak
            $data['latitude']     = null;
            $data['longitude']    = null;
            $data['jarak_meter']  = null;
            // Kalau GPS wajib (radius aktif) tapi tidak ada data → invalid tapi tetap simpan
            $data['lokasi_valid'] = true;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Data sekretariat untuk Alpine JS di blade.
     */
    public function getSekretariatData(): array
    {
        $lat = (float) SiteSetting::get('piket_lat', '0');
        $lng = (float) SiteSetting::get('piket_lng', '0');
        return [
            'sekLat' => $lat,
            'sekLng' => $lng,
            'radius' => (int) SiteSetting::get('piket_radius_meter', '50'),
            'aktif'  => $lat != 0.0 && $lng != 0.0,
        ];
    }
}
