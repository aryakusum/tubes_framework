<?php

namespace App\Filament\Resources\CoaResource\Pages;

use App\Filament\Resources\CoaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoa extends EditRecord
{
    protected static string $resource = CoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
