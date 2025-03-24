<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MakananResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_makanan' => $this->nama_makanan,
            'deskripsi_makanan' => $this->deskripsi_makanan,
            'harga_makanan' => $this->getFormattedPriceAttribute(),
            'stok_makanan' => $this->stok_makanan,
            'gambar' => $this->gambar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
