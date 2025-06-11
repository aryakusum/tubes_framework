<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TotalPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan per Menu per Bulan';

    protected function getData(): array
    {
        // Ambil data penjualan per menu per bulan dari tabel penjualan_makanan JOIN makanan
    $data = DB::table('penjualan_makanan')
    ->join('makanan', 'penjualan_makanan.makanan_id', '=', 'makanan.id')
    ->select([
        'makanan.nama_makanan as nama_makanan',
        DB::raw('MONTH(penjualan_makanan.created_at) as bulan'),
        DB::raw('SUM(penjualan_makanan.harga_jual * penjualan_makanan.jml) as total')
    ])
    ->groupBy('nama_makanan', 'bulan')
    ->orderBy('bulan')
    ->get();


        // Ambil semua bulan yang muncul
        $bulanList = $data->pluck('bulan')->unique()->sort()->values();

        $labels = $bulanList->map(function ($bulan) {
            return date('F', mktime(0, 0, 0, $bulan, 10));
        });

        // Ambil semua nama menu unik
        $menus = $data->pluck('nama_makanan')->unique();

        $datasets = [];

        foreach ($menus as $menu) {
            $dataset = [
                'label' => $menu,
                'data' => [],
                'backgroundColor' => $this->generateColor($menu),
            ];

            foreach ($bulanList as $bulan) {
                $total = $data
                    ->first(fn($row) => $row->nama_makanan == $menu && $row->bulan == $bulan)
                    ->total ?? 0;

                $dataset['data'][] = $total;
            }

            $datasets[] = $dataset;
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Fungsi untuk membuat warna acak yang konsisten
    protected function generateColor(string $key): string
    {
        $hash = crc32($key);
        $r = ($hash & 0xFF0000) >> 16;
        $g = ($hash & 0x00FF00) >> 8;
        $b = $hash & 0x0000FF;

        return "rgba($r, $g, $b, 0.7)";
    }
}
