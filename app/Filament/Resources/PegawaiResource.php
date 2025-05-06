<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;

use Filament\Tables\Columns\TextColumn;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Grid;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Grid::make(1) // Membuat hanya 1 kolom
            ->schema([
                TextInput::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->required()
                    ->placeholder('Masukkan nama pegawai')
                ,
                Radio::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required()
                ,
                Radio::make('jenis_Pegawai')
                    ->label('Jenis Pegawai')
                    ->options([
                        'Pegawai' => 'Pegawai',
                        'Kurir' => 'Kurir',
                    ])
                    ->required()
                ,
                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->placeholder('Masukkan jabatan')
                ,
                TextInput::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukkan alamat')
                ,
                TextInput::make('no_telp')
                    ->label('No Telp')
                    ->required()
                    ->placeholder('Masukkan no telp')
                ,
                DatePicker::make('tgl_masuk')
                    ->label('Tanggal masuk')
                    ->required()
                ,
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pegawai')
                ->label('Nama Pegawai')
                ->searchable()
                ->sortable(),

            TextColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->sortable(),

                TextColumn::make('jenis_Pegawai')
                ->label('Jenis Pegawai')
                ->sortable()
                ->searchable(), 
            
            TextColumn::make('jabatan')
                ->label('Jabatan')
                ->sortable(),

            TextColumn::make('alamat')
                ->label('Alamat')
                ->sortable(),

            TextColumn::make('no_telp')
                ->label('No Telp')
                ->sortable(),

            TextColumn::make('tgl_masuk')
                ->label('Tanggal Masuk')
                ->sortable(),
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
            ]);
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
