<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuBesarResource\Pages;
use App\Models\JurnalDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Coa;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class BukuBesarResource extends Resource
{
    protected static ?string $model = JurnalDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku Besar';

    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('coa_id')
                    ->label('Akun')
                    ->options(Coa::all()->pluck('nama_akun', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jurnal.tgl')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('jurnal.no_referensi')
                    ->label('No. Referensi')
                    ->searchable(),
                TextColumn::make('coa.nama_akun')
                    ->label('Akun')
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->label('Keterangan')
                    ->searchable(),
                TextColumn::make('debit')
                    ->label('Debit')
                    ->money('IDR')
                    ->alignRight(),
                TextColumn::make('credit')
                    ->label('Kredit')
                    ->money('IDR')
                    ->alignRight(),
            ])
            ->filters([
                SelectFilter::make('coa_id')
                    ->label('Akun')
                    ->options(Coa::all()->pluck('nama_akun', 'id'))
                    ->searchable(),
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),
                        DatePicker::make('end_date')
                            ->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereHas('jurnal', fn($q) => $q->whereDate('tgl', '>=', $date)),
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereHas('jurnal', fn($q) => $q->whereDate('tgl', '<=', $date)),
                            );
                    })
            ])
            ->defaultSort('jurnal.tgl', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ViewBukuBesar::route('/'),
        ];
    }
}
