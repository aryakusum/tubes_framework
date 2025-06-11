<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TotalPresensiChart extends ChartWidget
{
    protected static ?string $heading = 'Total Presensi per Karyawan per Bulan';

    protected function getData(): array
    {
        // Ambil data presensi per karyawan per bulan
        $data = DB::table('presensi')
            ->join('pegawai', 'presensi.id_pegawai', '=', 'pegawai.id')
            ->join('users', 'pegawai.user_id', '=', 'users.id')
            ->select([
                'users.name as nama_karyawan',
                DB::raw('MONTH(presensi.tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            ])
            ->groupBy('users.name', DB::raw('MONTH(presensi.tanggal)'))
            ->orderBy(DB::raw('MONTH(presensi.tanggal)'))
            ->get();

        // Ambil semua bulan unik
        $bulanList = $data->pluck('bulan')->unique()->sort()->values();

        $labels = $bulanList->map(function ($bulan) {
            return date('F', mktime(0, 0, 0, $bulan, 10)); // Nama bulan
        });

        // Ambil nama karyawan unik
        $karyawans = $data->pluck('nama_karyawan')->unique();

        $datasets = [];

        foreach ($karyawans as $karyawan) {
            $dataset = [
                'label' => $karyawan,
                'data' => [],
                'backgroundColor' => $this->generateColor($karyawan),
            ];

            foreach ($bulanList as $bulan) {
                $total = $data
                    ->first(fn($row) => $row->nama_karyawan === $karyawan && $row->bulan == $bulan)
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
        return 'bar'; // Bisa diganti ke 'line' jika ingin line chart
    }

    protected function generateColor(string $key): string
    {
        $hash = crc32($key);
        $r = ($hash & 0xFF0000) >> 16;
        $g = ($hash & 0x00FF00) >> 8;
        $b = $hash & 0x0000FF;

        return "rgba($r, $g, $b, 0.7)";
    }
}
