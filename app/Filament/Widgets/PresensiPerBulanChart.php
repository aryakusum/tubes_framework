<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Presensi;
use App\Models\Pegawai;
use Carbon\Carbon;

class PresensiPerBulanChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return 'Jumlah Kehadiran Pegawai per Bulan ' . date('Y');
    }

    protected function getData(): array
    {
        $year = now()->year;

        // Hitung jumlah kehadiran (status Hadir) tiap bulan
        $presensi = Presensi::query()
            ->where('status', 'Hadir')
            ->whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as month, COUNT(*) as total_hadir')
            ->groupBy('month')
            ->pluck('total_hadir', 'month');

        $allMonths = collect(range(1, 12));

        $data = $allMonths->map(function ($month) use ($presensi) {
            return $presensi->get($month, 0);
        });

        $labels = $allMonths->map(function ($month) {
            return Carbon::create()->month($month)->locale('id')->translatedFormat('F');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Hadir',
                    'data' => $data,
                    'backgroundColor' => '#4CAF50',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
