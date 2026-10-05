<?php

namespace App\Filament\Resources\LaporanPiketResource\Pages;

use App\Filament\Resources\LaporanPiketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPikets extends ListRecords
{
    protected static string $resource = LaporanPiketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Upload Bukti Piket')
                ->visible(fn (): bool => auth()->user()?->canUploadPiket() ?? false),
        ];
    }
}
