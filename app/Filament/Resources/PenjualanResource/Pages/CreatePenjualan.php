<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

// tambahan untuk akses ke penjualanmakanan
use App\Models\Penjualan;
use App\Models\PenjualanMakanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

// untuk notifikasi
use Filament\Notifications\Notification;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    //penanganan kalau status masih kosong 
    protected function beforeCreate(): void
    {
        $this->data['status'] = $this->data['status'] ?? 'pesan';
    }

    // tambahan untuk simpan
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

    // penanganan
    protected function simpanPembayaran()
    {
        // $penjualan = $this->record; // Ambil data penjualan yang sedang dibuat
        $penjualan = $this->record ?? Penjualan::latest()->first(); // Ambil penjualan terbaru jika null
        // Simpan ke tabel pembayaran2
        Pembayaran::create([
            'penjualan_id' => $penjualan->id,
            'tgl_bayar'    => now(),
            'jenis_pembayaran' => 'tunai',
            'transaction_time' => now(),
            'gross_amount'       => $penjualan->tagihan, // Sesuaikan dengan field di tabel pembayaran
            'order_id' => $penjualan->no_faktur,
        ]);

        // Update status penjualan jadi "dibayar"
        $penjualan->update(['status' => 'bayar']);

        // Notifikasi sukses
        Notification::make()
            ->title('Pembayaran Berhasil!')
            ->success()
            ->send();
    }
}
