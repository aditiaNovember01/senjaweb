<?php

namespace App\Filament\Resources\KegiatanResource\Pages;

use App\Filament\Resources\KegiatanResource;
use App\Models\Kegiatan;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKegiatans extends ListRecords
{
    protected static string $resource = KegiatanResource::class;

    public function mount(): void
    {
        parent::mount();
        // Sinkronisasi status otomatis saat halaman dibuka
        Kegiatan::syncAllStatuses();
    }

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
