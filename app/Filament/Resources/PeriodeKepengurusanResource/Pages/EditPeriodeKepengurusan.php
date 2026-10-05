<?php

namespace App\Filament\Resources\PeriodeKepengurusanResource\Pages;

use App\Filament\Resources\PeriodeKepengurusanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPeriodeKepengurusan extends EditRecord
{
    protected static string $resource = PeriodeKepengurusanResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
