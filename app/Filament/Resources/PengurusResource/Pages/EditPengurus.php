<?php

namespace App\Filament\Resources\PengurusResource\Pages;

use App\Filament\Resources\PengurusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengurus extends EditRecord
{
    protected static string $resource = PengurusResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
