<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use App\Models\Penggajian;
use Filament\Resources\Pages\CreateRecord;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['nomor_penggajian'] = Penggajian::generateNomorPenggajian();
        $data['total_gaji'] = 0;

        return $data;
    }
}
