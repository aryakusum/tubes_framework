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

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $navigationLabel = 'Data Makanan';

    protected static ?string $modelLabel = 'Makanan';

    protected static ?string $pluralModelLabel = 'Makanan';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode_makanan')
                ->required()
                ->default(function () {
                    $lastMakanan = Makanan::orderBy('id', 'desc')->first();
                    if (!$lastMakanan) {
                        return 'MKN01';
                    }

                    // Ambil angka dari kode terakhir
                    $lastNumber = (int) substr($lastMakanan->kode_makanan, 3);
                    // Generate nomor baru
                    $newNumber = $lastNumber + 1;
                    // Format dengan leading zero
                    return 'MKN' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
                })
                ->disabled()
                ->dehydrated(),
            TextInput::make('nama_makanan')
                ->required()
                ->maxLength(255),
            Toggle::make('halal')
                ->required()
                ->default(true)
                ->label('Halal')
                ->helperText('Apakah makanan ini halal?'),
            Textarea::make('deskripsi_makanan')
                ->required()
                ->rows(3)
                ->columnSpan('full'),
            TextInput::make('harga_makanan')
                ->required()
                ->numeric()
                ->prefix('Rp'),
            TextInput::make('stok_makanan')
                ->required()
                ->numeric(),
            FileUpload::make('gambar')
                ->image()
                ->directory('makanan-images')
                ->nullable(),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('kode_makanan')
                ->searchable()
                ->sortable(),
            TextColumn::make('nama_makanan')
                ->searchable()
                ->sortable(),
            IconColumn::make('halal')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->label('Status Halal'),
            TextColumn::make('deskripsi_makanan')
                ->searchable()
                ->wrap()
                ->limit(100)
                ->tooltip(function (TextColumn $column): ?string {
                    return $column->getState();
                })
                ->html()
                ->columnSpanFull(),
            TextColumn::make('harga_makanan')
                ->money('idr')
                ->sortable(),
            TextColumn::make('stok_makanan')
                ->sortable(),
            ImageColumn::make('gambar')
                ->square()
                ->height(100),
            TextColumn::make('created_at')
                ->dateTime()
                ->label('Tanggal Dibuat'),
        ])
            ->defaultSort('kode_makanan', 'asc')
            ->filters([
                Filter::make('stok_makanan')
                    ->query(fn($query) => $query->where('stok_makanan', '>', 0)),
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
        return [];
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
