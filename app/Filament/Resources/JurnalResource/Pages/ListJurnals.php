<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// tambahan
use App\Filament\Resources\JurnalResource\Widgets\JurnalTableOverview;

class ListJurnals extends ListRecords
{
    protected static string $resource = JurnalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // tambahan
    protected function getHeaderWidgets(): array
    {
        return [
            JurnalTableOverview::class,
        ];
    }
}