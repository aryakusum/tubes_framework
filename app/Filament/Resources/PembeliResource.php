<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembeliResource\Pages;
use App\Filament\Resources\PembeliResource\RelationManagers;
use App\Models\Pembeli;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// untuk form dan table
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

// untuk model ke user
use App\Models\User;

class PembeliResource extends Resource
{
    protected static ?string $model = Pembeli::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    // merubah nama label menjadi Pembeli
    protected static ?string $navigationLabel = 'Pembeli';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //direlasikan ke tabel user
                Select::make('user_id')
                    ->label('User Id')
                    ->relationship('user', 'email')
                    ->searchable() // Menambahkan fitur pencarian
                    ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $user = User::find($state);
                            $set('nama_pembeli', $user->name);
                        }
                    })
                , 

                TextInput::make('kode_pembeli')
                    ->default(fn () => Pembeli::getKodePembeli()) // Ambil default dari method getKodePembeli
                    ->label('Kode Pembeli')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,
                TextInput::make('nama_pembeli')
                    ->required()
                    ->placeholder('Masukkan nama pembeli') // Placeholder untuk membantu pengguna
                    // ->live()
                    ->readonly() // Membuat field tidak bisa diketik manual
                ,
                TextInput::make('alamat')
                    ->required()
                    ->placeholder('Masukkan alamat pembeli') // Placeholder untuk membantu pengguna
                ,
                TextInput::make('telepon')
                    ->required()
                    ->placeholder('Masukkan nomor telepon') // Placeholder untuk membantu pengguna
                    ->numeric() // Validasi agar hanya angka yang diizinkan
                    ->prefix('+62') // Contoh: Menambahkan prefix jika diperlukan
                    ->extraAttributes(['pattern' => '^[0-9]+$', 'title' => 'Masukkan angka yang diawali dengan 0']) // Validasi dengan pattern regex
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pembeli'),
                TextColumn::make('nama_pembeli'),
                TextColumn::make('alamat'),
                TextColumn::make('telepon'),
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
            'index' => Pages\ListPembelis::route('/'),
            'create' => Pages\CreatePembeli::route('/create'),
            'edit' => Pages\EditPembeli::route('/{record}/edit'),
        ];
    }
}
