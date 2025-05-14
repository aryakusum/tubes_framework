<?php

namespace App\Filament\Resources\PembeliResource\Pages;

use App\Filament\Resources\PembeliResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelis extends ListRecords
{
    protected static string $resource = PembeliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
