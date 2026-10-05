<?php

namespace App\Filament\Resources\DivisiResource\Pages;

use App\Filament\Resources\DivisiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDivisi extends EditRecord
{
    protected static string $resource = DivisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
