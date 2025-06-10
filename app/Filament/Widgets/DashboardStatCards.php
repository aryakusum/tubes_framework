<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

// tambahan
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Penjualan;
use App\Models\Coa;
use App\Models\Pembeli;

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

        return [
            Stat::make('Total Pembeli', Pembeli::count())
                ->description('Jumlah pembeli terdaftar')
            ,
            Stat::make('Total Transaksi', Penjualan::count())
                ->description('Jumlah transaksi')
            ,
            Stat::make('Total Penjualan', rupiah(
                        Penjualan::query()
                        ->where('status', 'bayar') // Filter hanya yang statusnya 'bayar'
                        ->sum('tagihan')
                    ))
                ->description('Jumlah transaksi terbayar')
            ,
            Stat::make('Total Keuntungan', rupiah(
                Penjualan::query()
                ->join('penjualan_makanan', 'penjualan.id', '=', 'penjualan_makanan.penjualan_id') 
                ->where('status', 'bayar') // Filter hanya yang statusnya 'bayar'
                ->selectRaw('SUM((penjualan_makanan.harga_jual - penjualan_makanan.harga_beli) * penjualan_makanan.jml) as total_penjualan') // Perhitungan total penjualan
                ->value('total_penjualan') // Ambil hasil perhitungan
            ))
                ->description('Jumlah keuntungan')
            ,
            Stat::make('Revenue', '$' . $formatNumber($revenue))
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }

    // tambahan untuk kartu
    protected function getCards(): array
    {
        return [
            // Card::make('Total Transaksi', Penjualan::count())
            //     ->description('Jumlah transaksi yang tercatat')
            //     // ->color('primary')
            // ,
            // Card::make('Total Pendapatan', 'Rp ' . number_format(\App\Models\Transaksi::sum('total')))
            //     ->description('Total uang masuk')
            //     ->color('success'),

            // Card::make('Jumlah Akun COA', Coa::count())
            //     ->description('Data akun aktif')
            //     ->color('warning'),
        ];
    }
}