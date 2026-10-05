<?php

namespace App\Filament\Resources\ProgramKerjaResource\Pages;

use App\Filament\Resources\ProgramKerjaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramKerjas extends ListRecords
{
    protected static string $resource = ProgramKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
