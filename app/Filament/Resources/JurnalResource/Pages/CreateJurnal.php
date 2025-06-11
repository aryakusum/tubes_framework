<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

// tambahan
use Illuminate\Validation\ValidationException;

class CreateJurnal extends CreateRecord
{
    protected static string $resource = JurnalResource::class;

    // tambahan
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     if (!isset($data['details']) || !is_array($data['details'])) {
    //         throw ValidationException::withMessages([
    //             'details' => 'Detail jurnal wajib diisi.',
    //         ]);
    //     }

    //     $totalDebit = collect($data['details'])->sum('debit');
    //     $totalCredit = collect($data['details'])->sum('credit');

    //     if ($totalDebit != $totalCredit) {
    //         throw ValidationException::withMessages([
    //             'details' => 'Total debit dan kredit harus sama.',
    //         ]);
    //     }

    //     return $data;
    // }
}