<?php

namespace App\Filament\Resources\JadwalPiketResource\Pages;

use App\Filament\Resources\JadwalPiketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPikets extends ListRecords
{
    protected static string $resource = JadwalPiketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => JadwalPiketResource::canCreate()),
        ];
    }
}
