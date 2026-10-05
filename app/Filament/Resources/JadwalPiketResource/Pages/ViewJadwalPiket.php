<?php

namespace App\Filament\Resources\JadwalPiketResource\Pages;

use App\Filament\Resources\JadwalPiketResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalPiket extends ViewRecord
{
    protected static string $resource = JadwalPiketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => JadwalPiketResource::canEdit($this->record)),
        ];
    }
}
