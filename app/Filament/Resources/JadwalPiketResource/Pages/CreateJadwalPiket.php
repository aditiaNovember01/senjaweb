<?php

namespace App\Filament\Resources\JadwalPiketResource\Pages;

use App\Filament\Resources\JadwalPiketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalPiket extends CreateRecord
{
    protected static string $resource = JadwalPiketResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
