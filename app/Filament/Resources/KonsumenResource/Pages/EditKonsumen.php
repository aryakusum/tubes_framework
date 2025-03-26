<?php

namespace App\Filament\Resources\KonsumenResource\Pages;

use App\Filament\Resources\KonsumenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKonsumen extends EditRecord
{
    protected static string $resource = KonsumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
