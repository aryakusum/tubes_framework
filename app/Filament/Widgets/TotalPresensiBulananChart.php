<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget; // ✅ ini benar
use App\Models\Presensi;
use App\Models\Pegawai;
use Carbon\Carbon;

class TotalPresensiBulananChart extends BarChartWidget
{
    protected static ?string $heading = 'Grafik Kehadiran Pegawai (30 Hari Terakhir)';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(30)->toDateString();
        $endDate = Carbon::now()->toDateString();

        $pegawaiList = Pegawai::all();

        $labels = [];
        $data = [];

        foreach ($pegawaiList as $pegawai) {
            $hadirCount = Presensi::where('id_pegawai', $pegawai->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'hadir')
                ->count();

            $labels[] = $pegawai->nama;
            $data[] = $hadirCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Hari Hadir',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
