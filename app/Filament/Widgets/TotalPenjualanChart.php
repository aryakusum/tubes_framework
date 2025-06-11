<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;

class TotalPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan Chart';

    protected function getData(): array
    {
        // Ambil data total penjualan per pembeli
        $data = Penjualan::query()
            ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
            ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('pembeli.nama_pembeli, SUM(penjualan_makanan.harga_jual * penjualan_makanan.jml) as total_penjualan')
            ->groupBy('pembeli.nama_pembeli')
            ->orderByDesc('total_penjualan')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_pembeli' => $item->nama_pembeli,
                    'total_penjualan' => $item->total_penjualan,
                ];
            });

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total_penjualan')->toArray(),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $data->pluck('nama_pembeli')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
