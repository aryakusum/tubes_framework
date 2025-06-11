<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

// tambahan
use Filament\Exceptions\Halt;

class EditJurnal extends EditRecord
{
    protected static string $resource = JurnalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // tambahan
    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     if (!isset($data['details']) || !is_array($data['details'])) {
    //         throw Halt::make()->withValidationErrors([
    //             'details' => 'Detail jurnal wajib diisi.',
    //         ]);
    //     }

    //     $totalDebit = collect($data['details'])->sum('debit');
    //     $totalCredit = collect($data['details'])->sum('credit');

    //     if ($totalDebit != $totalCredit) {
    //         throw \Filament\Exceptions\Halt::make()->withValidationErrors([
    //             'details' => 'Total debit dan kredit harus sama.',
    //         ]);
    //     }

    //     return $data;
    // }

}