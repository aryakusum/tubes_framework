<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

// Model yang digunakan
use App\Models\Penjualan;
use App\Models\Pembayaran;

// Notifikasi
use Filament\Notifications\Notification;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    // Default status jika belum diisi
    protected function beforeCreate(): void
    {
        $this->data['status'] = $this->data['status'] ?? 'pesan';
    }

    // Tambahkan tombol "Bayar"
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('bayar')
                ->label('Bayar')
                ->color('success')
                ->action(fn () => $this->simpanPembayaran())
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin menyimpan pembayaran ini?')
                ->modalButton('Ya, Bayar'),
        ];
    }

    // Simpan data pembayaran
    protected function simpanPembayaran()
    {
        $penjualan = $this->record ?? Penjualan::latest()->first();

        // Hitung total tagihan dari relasi penjualan_makanan
        $tagihan = $penjualan->penjualanMakanan->sum(function ($item) {
            return $item->harga_jual * $item->jml;
        });

        // Simpan pembayaran
        Pembayaran::create([
            'penjualan_id'      => $penjualan->id,
            'tgl_bayar'         => now(),
            'jenis_pembayaran'  => 'tunai',
            'transaction_time'  => now(),
            'gross_amount'      => $tagihan,
            'order_id'          => $penjualan->no_faktur,
        ]);

        // Update status dan tagihan di penjualan
        $penjualan->update([
            'status'  => 'bayar',
            'tagihan' => $tagihan,
        ]);

        // Notifikasi berhasil
        Notification::make()
            ->title('Pembayaran Berhasil!')
            ->success()
            ->send();
    }
}
