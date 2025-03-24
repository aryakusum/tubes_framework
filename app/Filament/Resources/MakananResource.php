<?php

namespace App\Filament\Resources;


use App\Filament\Resources\MakananResource\Pages;
use App\Models\Makanan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource as FilamentResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables;



class MakananResource extends FilamentResource
{
    protected static ?string $model = Makanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            
                TextInput::make('nama_makanan')
                    ->required()
                    ->placeholder('Masukkan nama makanan'),

                Textarea::make('deskripsi_makanan')
                    ->required()
                    ->label('Deskripsi Makanan')
                    ->columnSpanFull(),

                TextInput::make('harga_makanan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->placeholder('Masukkan harga makanan')
                    ->mask('999,999,999,999,999'),

                TextInput::make('stok_makanan')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan stok makanan'),

                FileUpload::make('gambar')
                    ->image()
                    ->directory('uploads/makanan')
                    ->label('Gambar Makanan')
                    ->columnSpanFull(),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')
                ->sortable()
                ->label('ID')
                ->formatStateUsing(fn (string $state): string => 'Mkn' . str_pad($state, 2, '0', STR_PAD_LEFT)),


            TextColumn::make('nama_makanan')
                ->sortable()
                ->searchable()
                ->label('Nama Makanan'),

            TextColumn::make('deskripsi_makanan')
                ->limit(50)
                ->label('Deskripsi'),

            TextColumn::make('harga_makanan')
                ->label('Harga Makanan')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->extraAttributes(['class' => 'text-right'])
                ->sortable(),

            TextColumn::make('stok_makanan')
                ->sortable()
                ->label('Stok Makanan'),

            ImageColumn::make('gambar')
                ->label('Gambar')
                ->size(50)
                ->getStateUsing(fn ($record) => asset('storage/' . $record->gambar)),

            TextColumn::make('created_at')
                ->dateTime()
                ->label('Tanggal Dibuat'),
        ])
        ->filters([
            Filter::make('stok_makanan')
                ->query(fn ($query) => $query->where('stok_makanan', '>', 0)),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\ViewAction::make(),
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMakanans::route('/'),
            'create' => Pages\CreateMakanan::route('/create'),
            'edit' => Pages\EditMakanan::route('/{record}/edit'),
        ];
    }
}
 