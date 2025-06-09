<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Presensi;
use App\Models\Pegawai;
use Carbon\Carbon;

class DashboardStatCards extends BaseWidget
{
    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        // Contoh filter atau kalkulasi bisa disesuaikan, ini dummy contoh mirip kodenya kamu
        $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

        // Dummy kalkulasi, bisa diganti dengan logika yang sesuai
        $totalPresensi = Presensi::whereBetween('tanggal', [$startDate ?? now()->subDays(30), $endDate])
            ->count();

        $totalPegawai = Pegawai::count();

        $hadirCount = Presensi::whereBetween('tanggal', [$startDate ?? now()->subDays(30), $endDate])
            ->where('status', 'hadir')
            ->count();

        $izinCount = Presensi::whereBetween('tanggal', [$startDate ?? now()->subDays(30), $endDate])
            ->where('status', 'izin')
            ->count();

        $sakitCount = Presensi::whereBetween('tanggal', [$startDate ?? now()->subDays(30), $endDate])
            ->where('status', 'sakit')
            ->count();

        return [
            Stat::make('Total Pegawai', $totalPegawai)
                ->description('Jumlah pegawai terdaftar'),

            Stat::make('Total Presensi', $totalPresensi)
                ->description('Jumlah data presensi (semua status)'),

            Stat::make('Hadir', $hadirCount)
                ->description('Jumlah kehadiran pegawai'),

            Stat::make('Izin', $izinCount)
                ->description('Jumlah pegawai izin'),

            Stat::make('Sakit', $sakitCount)
                ->description('Jumlah pegawai sakit'),
        ];
    }

    protected function getCards(): array
    {
        return [
            // Kalau kamu mau tambahan card stat juga bisa ditambahkan di sini
        ];
    }
}
