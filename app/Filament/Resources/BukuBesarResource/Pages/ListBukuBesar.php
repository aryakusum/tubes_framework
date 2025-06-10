<?php

namespace App\Filament\Resources\BukuBesarResource\Pages;

use App\Filament\Resources\BukuBesarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBukuBesar extends ListRecords
{
    protected static string $resource = BukuBesarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('export')
            //     ->label('Export')
            //     ->icon('heroicon-o-arrow-down-tray')
            //     ->url(fn () => route('filament.admin.resources.buku-besar.export'))
            //     ->openUrlInNewTab(),
        ];
    }
}
