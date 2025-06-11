<?php

namespace App\Filament\Resources\BukuBesarResource\Pages;

use App\Filament\Resources\BukuBesarResource;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Coa;
use App\Models\JurnalDetail;

class ViewBukuBesar extends Page
{

    protected static string $resource = BukuBesarResource::class;

    protected static string $view = 'filament.resources.buku-besar-resource.pages.view-buku-besar';

    public float $beginning_balance = 0;
    public array $transactions = [];
    public float $ending_balance = 0;
    public array $data = [];

    public function mount(): void
    {
        // Initialize data for form fields
        $this->data = [
            'coa_id' => null,
            'start_date' => null,
            'end_date' => null,
        ];
        $this->generateReport(); // Generate report on initial load if data is set
    }

    public function generateReport(): void
    {
        $coaId = $this->data['coa_id'] ?? null;
        $startDate = $this->data['start_date'] ?? null;
        $endDate = $this->data['end_date'] ?? null;

        if ($coaId && $startDate && $endDate) {
            // Calculate beginning balance
            $coa = Coa::find($coaId);
            $this->beginning_balance = $coa->nominal ?? 0; // Get nominal from Coa table

            $beginningBalanceFromTransactions = JurnalDetail::query()
                ->where('coa_id', $coaId)
                ->join('jurnal', 'jurnal_detail.jurnal_id', '=', 'jurnal.id')
                ->whereDate('jurnal.tgl', '<', $startDate)
                ->sum('debit') - JurnalDetail::query()
                ->where('coa_id', $coaId)
                ->join('jurnal', 'jurnal_detail.jurnal_id', '=', 'jurnal.id')
                ->whereDate('jurnal.tgl', '<', $startDate)
                ->sum('credit');

            $this->beginning_balance += $beginningBalanceFromTransactions;

            // Get transactions
            $transactionsQuery = JurnalDetail::query()
                ->where('coa_id', $coaId)
                ->join('jurnal', 'jurnal_detail.jurnal_id', '=', 'jurnal.id')
                ->whereBetween('jurnal.tgl', [$startDate, $endDate])
                ->select('jurnal_detail.*', 'jurnal.tgl')
                ->orderBy('jurnal.tgl', 'asc')
                ->orderBy('jurnal_detail.id')
                ->get();

            $currentBalance = $this->beginning_balance;
            $this->transactions = [];

            foreach ($transactionsQuery as $transaction) {
                $currentBalance += $transaction->debit;
                $currentBalance -= $transaction->credit;
                $this->transactions[] = [
                    'tgl' => $transaction->jurnal->tgl,
                    'no_referensi' => $transaction->jurnal->no_referensi,
                    'deskripsi' => $transaction->deskripsi,
                    'debit' => $transaction->debit,
                    'credit' => $transaction->credit,
                    'running_balance' => $currentBalance,
                ];
            }

            $this->ending_balance = $currentBalance;
        } else {
            $this->beginning_balance = 0;
            $this->transactions = [];
            $this->ending_balance = 0;
        }
    }

    public function getHeading(): string
    {
        return 'Buku Besar';
    }
}
