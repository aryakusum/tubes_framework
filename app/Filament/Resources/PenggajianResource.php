<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Models\Penggajian;
use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\DetailPenggajian;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\SlipGajiMail;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Penggajian';
    protected static ?string $modelLabel = 'Penggajian';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_penggajian')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->default(function () {
                                return Penggajian::generateNomorPenggajian();
                            })
                            ->dehydrated(),

                        Forms\Components\DatePicker::make('tanggal_penggajian')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('periode_awal')
                            ->required(),

                        Forms\Components\DatePicker::make('periode_akhir')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Textarea::make('keterangan')
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Wizard\Step::make('Detail Penggajian')
                    ->schema([
                        Forms\Components\Repeater::make('detailPenggajians')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('pegawai_id')
                                    ->label('Pegawai')
                                    ->searchable()
                                    ->preload()
                                    ->options(function () {
                                        return Pegawai::query()
                                            ->select('id', 'nama_pegawai')
                                            ->orderBy('nama_pegawai')
                                            ->get()
                                            ->pluck('nama_pegawai', 'id');
                                    })
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        if (!$state) return;

                                        $periode_awal = $get('../../periode_awal');
                                        $periode_akhir = $get('../../periode_akhir');

                                        if (!$periode_awal || !$periode_akhir) {
                                            $set('total_hadir', 0);
                                            $set('total_gaji', 0);
                                            return;
                                        }

                                        $hadir = DB::table('presensi')
                                            ->where('id_pegawai', $state)
                                            ->where('status', 'Hadir')
                                            ->whereBetween('tanggal', [
                                                Carbon::parse($periode_awal),
                                                Carbon::parse($periode_akhir)
                                            ])
                                            ->count();

                                        $gaji_pokok = 100000;
                                        $set('total_hadir', $hadir);
                                        $set('gaji_pokok', $gaji_pokok);
                                        $set('total_gaji', $hadir * $gaji_pokok);
                                    }),

                                Forms\Components\TextInput::make('total_hadir')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('gaji_pokok')
                                    ->label('Gaji per Hari')
                                    ->numeric()
                                    ->required()
                                    ->default(100000)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $total = ((float)$state * (float)$get('total_hadir')) +
                                            (float)($get('tunjangan') ?? 0) -
                                            (float)($get('potongan') ?? 0);
                                        $set('total_gaji', $total);
                                    }),

                                Forms\Components\TextInput::make('tunjangan')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $total = ((float)$get('gaji_pokok') * (float)$get('total_hadir')) +
                                            (float)($state ?? 0) -
                                            (float)($get('potongan') ?? 0);
                                        $set('total_gaji', $total);
                                    }),

                                Forms\Components\TextInput::make('potongan')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $total = ((float)$get('gaji_pokok') * (float)$get('total_hadir')) +
                                            (float)($get('tunjangan') ?? 0) -
                                            (float)($state ?? 0);
                                        $set('total_gaji', $total);
                                    }),

                                Forms\Components\TextInput::make('total_gaji')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->cloneable(false),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_penggajian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_penggajian')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_gaji')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'primary' => 'diproses',
                        'success' => 'selesai',
                        'danger' => 'dibatalkan',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('Bayar')
                        ->action(function ($record) {
                            $record->status = 'selesai';
                            $record->save();

                            // Kirim slip gaji ke masing-masing pegawai
                            foreach ($record->detailPenggajians as $detail) {
                                $pegawai = $detail->pegawai;
                                if ($pegawai && $pegawai->user && $pegawai->user->email) {
                                    Mail::to($pegawai->user->email)->send(new SlipGajiMail($record, $detail));
                                }
                            }

                            Notification::make()
                                ->title('Penggajian berhasil dibayar & slip gaji dikirim ke semua pegawai!')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}
