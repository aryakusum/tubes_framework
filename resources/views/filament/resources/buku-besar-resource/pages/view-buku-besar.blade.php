<x-filament-panels::page>
    <form wire:submit.prevent="generateReport">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="coa_id" class="block text-sm font-medium text-gray-700">Akun</label>
                <select wire:model.live="data.coa_id" id="coa_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Pilih Akun</option>
                    @foreach(\App\Models\Coa::all()->pluck('nama_akun', 'id') as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('data.coa_id')<p class="mt-2 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" wire:model.live="data.start_date" id="start_date" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500" />
                @error('data.start_date')<p class="mt-2 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                <input type="date" wire:model.live="data.end_date" id="end_date" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500" />
                @error('data.end_date')<p class="mt-2 text-sm text-danger-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6">
            <x-filament::button type="submit">
                Generate Laporan
            </x-filament::button>
        </div>
    </form>

    @if ($this->data['coa_id'] && $this->data['start_date'] && $this->data['end_date'])
    <div class="mt-8 space-y-6">
        <x-filament::card>
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">Buku Besar</h3>
                    <p class="text-gray-500">
                        {{ \App\Models\Coa::find($this->data['coa_id'])->nama_akun }}
                    </p>
                    <p class="text-gray-500">
                        Periode: {{ \Carbon\Carbon::parse($this->data['start_date'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($this->data['end_date'])->format('d M Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500">Saldo Awal</p>
                    <p class="text-lg font-bold">{{ 'Rp ' . number_format($this->beginning_balance, 0, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::card>

        <x-filament::card>
            <h3 class="text-lg font-bold mb-4">Transaksi</h3>
            @if (count($this->transactions) > 0)
            <table class="w-full text-sm text-left border border-gray-200">
                <thead></thead>
                <tr>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">No. Referensi</th>
                    <th class="px-4 py-2 border">Keterangan</th>
                    <th class="px-4 py-2 border text-right">Debit</th>
                    <th class="px-4 py-2 border text-right">Kredit</th>
                    <th class="px-4 py-2 border text-right">Saldo</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($this->transactions as $transaction)
                    <tr>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($transaction['tgl'])->format('d M Y') }}</td>
                        <td class="px-4 py-2 border">{{ $transaction['no_referensi'] }}</td>
                        <td class="px-4 py-2 border">{{ $transaction['deskripsi'] }}</td>
                        <td class="px-4 py-2 border text-right">{{ 'Rp ' . number_format($transaction['debit'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border text-right">{{ 'Rp ' . number_format($transaction['credit'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border text-right">{{ 'Rp ' . number_format($transaction['running_balance'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>Tidak ada transaksi dalam periode ini.</p>
            @endif
        </x-filament::card>

        <x-filament::card>
            <div class="flex justify-end">
                <div class="text-right">
                    <p class="text-gray-500">Saldo Akhir</p>
                    <p class="text-lg font-bold">{{ 'Rp ' . number_format($this->ending_balance, 0, ',', '.') }}</p>
                </div>
            </div>
        </x-filament::card>
    </div>
    @else
    <x-filament::card>
        <p>Silakan pilih akun dan periode tanggal untuk melihat laporan buku besar.</p>
    </x-filament::card>
    @endif
</x-filament-panels::page>