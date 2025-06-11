<?php

namespace App\Filament\Resources\JurnalResource\Widgets;

use Filament\Widgets\Widget;

// tambahan
use App\Models\Jurnal;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class JurnalTableOverview extends Widget
{
    protected static string $view = 'filament.resources.jurnal-resource.widgets.jurnal-table-overview';

    // tambahan
    protected int | string | array $columnSpan = 'full';
    
    public $periode; // properti untuk menyimpan periode

    protected $listeners = ['filterUpdated' => 'getViewData'];

    public function mount(): void
    {
        $this->periode = request('periode', now()->format('Y-m')); // Ambil periode dari URL atau set default
    }

    public function filterJurnal(): void
    {
        
    }

    // // public function getData(): array
    public function getViewData(): array
    {
        
        // dd($periode);
        $jurnalsQuery = Jurnal::with('jurnaldetail.coa');

        if ($this->periode) {
            [$year, $month] = explode('-', $this->periode);
            $jurnalsQuery->whereYear('tgl', $year)->whereMonth('tgl', $month);
        }

        $jurnals = $jurnalsQuery->get();

        return [
            'jurnals' => $jurnals,
            'periode' => $this->periode,
        ];
    }

}