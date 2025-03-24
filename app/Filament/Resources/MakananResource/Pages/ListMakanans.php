<?php

namespace App\Filament\Resources\MakananResource\Pages;

use App\Filament\Resources\MakananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMakanans extends ListRecords
{
    protected static string $resource = MakananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
