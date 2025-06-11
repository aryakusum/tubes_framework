<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MakananTerlarisChart extends ChartWidget
{
    protected static ?string $heading = 'Makanan Terlaris Chart';

    protected function getData(): array
    {
        $data = DB::table('penjualan_makanan')
            ->join('makanan', 'penjualan_makanan.makanan_id', '=', 'makanan.id')
            ->select('makanan.nama_makanan', DB::raw('SUM(penjualan_makanan.jml) as total_terjual'))
            ->groupBy('makanan.nama_makanan')
            ->orderByDesc('total_terjual')
            ->limit(5) // Limit to top 5 for clarity
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $data->pluck('total_terjual')->toArray(),
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $data->pluck('nama_makanan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
