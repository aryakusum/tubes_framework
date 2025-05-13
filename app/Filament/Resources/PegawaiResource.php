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
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pegawai';

    protected static ?string $modelLabel = 'Pegawai';

    protected static ?string $pluralModelLabel = 'Pegawai';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Akun User')
                    ->options(
                        User::where('user_group', 'pegawai')
                            ->whereNotIn('id', function ($query) {
                                $query->select('user_id')
                                    ->from('pegawai')
                                    ->whereNotNull('user_id');
                            })
                            ->pluck('email', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'email'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Hidden::make('user_group')
                            ->default('pegawai'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => Hash::make($data['password']),
                            'user_group' => 'Pegawai',
                        ])->id;
                    })
                    ->required(),
                Forms\Components\TextInput::make('nama_pegawai')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Pegawai'),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required()
                    ->label('Jenis Kelamin'),
                Forms\Components\Select::make('jenis_Pegawai')
                    ->options([
                        'Pegawai' => 'Pegawai',
                        'Kurir' => 'Kurir',
                    ])
                    ->required()
                    ->label('Jenis Pegawai'),
                Forms\Components\TextInput::make('jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('no_telp')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->label('No. Telepon'),
                Forms\Components\DatePicker::make('tgl_masuk')
                    ->required()
                    ->label('Tanggal Masuk'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pegawai')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pegawai'),
                Tables\Columns\TextColumn::make('user.email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable()
                    ->sortable()
                    ->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('jenis_Pegawai')
                    ->searchable()
                    ->sortable()
                    ->label('Jenis Pegawai'),
                Tables\Columns\TextColumn::make('jabatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->searchable()
                    ->label('No. Telepon'),
                Tables\Columns\TextColumn::make('tgl_masuk')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Masuk'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_Pegawai')
                    ->options([
                        'Pegawai' => 'Pegawai',
                        'Kurir' => 'Kurir',
                    ])
                    ->label('Jenis Pegawai'),
            ])
            ->actions([
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
