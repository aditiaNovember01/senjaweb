<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\SuratKeluarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuratKeluar extends EditRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
