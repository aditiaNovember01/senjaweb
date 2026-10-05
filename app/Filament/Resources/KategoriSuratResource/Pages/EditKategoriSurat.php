<?php

namespace App\Filament\Resources\KategoriSuratResource\Pages;

use App\Filament\Resources\KategoriSuratResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKategoriSurat extends EditRecord
{
    protected static string $resource = KategoriSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
