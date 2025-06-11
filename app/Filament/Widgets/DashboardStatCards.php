<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Penjualan;
use App\Models\Coa;
use App\Models\Pembeli;
use App\Models\Presensi;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Number;

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

        // Sales statistics
        $isBusinessCustomersOnly = $this->filters['businessCustomersOnly'] ?? null;
        $businessCustomerMultiplier = match (true) {
            boolval($isBusinessCustomersOnly) => 2 / 3,
            blank($isBusinessCustomersOnly) => 1,
            default => 1 / 3,
        };

        $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

        $revenue = (int) (($startDate ? ($diffInDays * 137) : 192100) * $businessCustomerMultiplier);
        $newCustomers = (int) (($startDate ? ($diffInDays * 7) : 1340) * $businessCustomerMultiplier);
        $newOrders = (int) (($startDate ? ($diffInDays * 13) : 3543) * $businessCustomerMultiplier);

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'k';
            }

            return Number::format($number / 1000000, 2) . 'm';
        };

        // Attendance statistics
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
            // Sales Stats
            Stat::make('Total Pembeli', Pembeli::count())
                ->description('Jumlah pembeli terdaftar'),
            Stat::make('Total Transaksi', Penjualan::count())
                ->description('Jumlah transaksi'),
            Stat::make('Total Penjualan', rupiah(
                Penjualan::query()
                ->where('status', 'bayar')
                ->sum('tagihan')
            ))
                ->description('Jumlah transaksi terbayar'),
            Stat::make('Total Keuntungan', rupiah(
                Penjualan::query()
                ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id')
                ->where('status', 'bayar')
                ->selectRaw('SUM((penjualan_makanan.harga_jual - penjualan_makanan.harga_beli) * penjualan_makanan.jml) as total_penjualan')
                ->value('total_penjualan')
            ))
                ->description('Jumlah keuntungan'),

            // Attendance Stats
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
            // Additional cards can be added here if needed
        ];
    }
}