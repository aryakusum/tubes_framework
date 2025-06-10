<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Filament\Resources\JurnalResource\RelationManagers;
use App\Models\Jurnal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// tambahan
use App\Models\Coa;
use App\Models\JurnalDetail;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Columns\TextColumn;

use Filament\Forms\Components\Section;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    // tambahan buat label Jurnal Umum
    protected static ?string $navigationLabel = 'Jurnal Umum';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Deskripsi Jurnal')
                ->schema([
                     //master tabel ke jurnal
                    DatePicker::make('tgl')
                    ->label('Tanggal')
                    ->required()
                    ->default(now())
                    ,
                    TextInput::make('no_referensi')->label('No Referensi')->maxLength(100),
                    Textarea::make('deskripsi')->label('Deskripsi'),
                ])->columns(1)
                ->collapsed() // <- Awalnya tertutup (collapsible)
                ->collapsible()
                , // <- Boleh di-expand manual,
                Section::make('Detail Jurnal')
                ->schema([
                    // transaksi tabel pakai repeater
                    Repeater::make('items')
                    ->label('Detail Jurnal')
                    ->relationship('jurnaldetail')
                    ->schema([
                        Select::make('coa_id')
                            ->label('Akun')
                            ->options(Coa::all()->pluck('nama_akun', 'id'))
                            ->searchable()
                            ->required(),
                        TextInput::make('debit')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('credit')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required(),
                        Textarea::make('deskripsi')->label('Keterangan')->rows(2),
                    ])
                    ->columns(1)
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Optional real-time validation logic if needed
                    }),
                ])
                ->collapsed() // <- Awalnya tertutup (collapsible)
                ->collapsible(), // <- Boleh di-expand manual
                
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tgl')->date(),
                TextColumn::make('no_referensi')->label('Ref'),
                TextColumn::make('deskripsi')->limit(30),
                TextColumn::make('jurnaldetail.debit')
                    ->label('Total Debit')
                    ->formatStateUsing(function ($state, $record) {
                        // Menghitung jumlah debit dari relasi jurnaldetail
                        // dd(var_dump($record));  // Debugging untuk melihat data relasi
                        $debit = $record->jurnaldetail()->sum('debit'); 
                        return 'Rp ' . number_format($debit, 0, ',', '.');
                    })
                    ->alignment('end') // Rata kanan
                , 
                TextColumn::make('jurnaldetail.credit')
                    ->label('Total Kredit')
                    ->formatStateUsing(function ($state, $record) {
                        $credit = $record->jurnaldetail()->sum('credit'); 
                        return 'Rp ' . number_format($credit, 0, ',', '.');
                    })
                    ->alignment('end') // Rata kanan
                , 
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tgl', 'desc')
            ;
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
            'index' => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit' => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}