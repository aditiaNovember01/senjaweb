<?php

namespace App\Filament\Resources\GaleriWebsiteResource\Pages;

use App\Filament\Resources\GaleriWebsiteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGaleriWebsite extends EditRecord
{
    protected static string $resource = GaleriWebsiteResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Jika sumber upload, isi field upload dari path_foto supaya preview tampil
        if (($data['sumber'] ?? '') === 'upload') {
            $data['path_foto_upload'] = $data['path_foto'] ?? null;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['sumber'] ?? '') === 'upload' && ! empty($data['path_foto_upload'])) {
            $data['path_foto'] = $data['path_foto_upload'];
        }

        unset($data['path_foto_upload']);

        return $data;
    }
}
