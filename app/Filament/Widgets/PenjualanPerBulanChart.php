<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;
use Carbon\Carbon;

class PenjualanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return 'Penjualan Per Bulan ' . date('Y');
    }

    protected function getData(): array
    {
        $year = now()->year;

        // Ambil data total penjualan dari penjualan_makanan
        $orders = Penjualan::query()
            ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
            ->where('penjualan.status', 'bayar')
            ->whereYear('penjualan.tgl', $year)
            ->selectRaw("MONTH(penjualan.tgl) as month, SUM(penjualan_makanan.harga_jual * penjualan_makanan.jml) as total_penjualan")
            ->groupBy('month')
            ->pluck('total_penjualan', 'month');

        $allMonths = collect(range(1, 12));

        $data = $allMonths->map(fn($month) => $orders->get($month, 0));

        $labels = $allMonths->map(
            fn($month) =>
            Carbon::create()->month($month)->locale('id')->translatedFormat('F')
        );

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
