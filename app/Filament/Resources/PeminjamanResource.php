<?php

namespace App\Filament\Resources;

use App\Exports\ExportAllData;
use App\Filament\Resources\PeminjamanResource\Pages;
use App\Imports\ImportInventaris;
use App\Imports\ImportPeminjaman;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationGroup = 'Inventaris dan Peminjaman';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'peminjaman';

    public static function getModelLabel(): string
    {
        return 'Peminjaman';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Peminjaman';
    }

    public static function getNavigationLabel(): string
    {
        return 'Peminjaman';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peminjam')
                    ->schema([
                        Forms\Components\Select::make('id_users')
                            ->relationship('user', 'name')
                            ->label('Nama peminjam')
                            ->searchable()
                            ->required()
                            ->disabled(fn($record) => $record !== null),

                        Forms\Components\Select::make('nim')
                            ->relationship('user', 'nim')
                            ->label('NIM')
                            ->searchable()
                            ->required()
                            ->disabled(fn($record) => $record !== null),

                        Forms\Components\DatePicker::make('tanggal_peminjaman')
                            ->required()
                            ->disabled(fn($record) => $record !== null),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->required()
                            ->after('tanggal_peminjaman')
                            ->disabled(fn($record) => $record !== null),

                        Forms\Components\Textarea::make('alasan')
                            ->label('Alasan Peminjaman')
                            ->disabled(fn($record) => $record !== null)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Status Peminjaman')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'diajukan' => 'Diajukan',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'dipinjam' => 'Dipinjam',
                                'jatuh tenggat' => 'Jatuh Tenggat',
                                'dikembalikan' => 'Dikembalikan',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Jika status dikembalikan, tambahkan field tanggal pengembalian
                                if ($state === 'dikembalikan') {
                                    $set('tanggal_pengembalian', now()->format('Y-m-d'));
                                }
                            })
                            ->default('diajukan'),

                        Forms\Components\DatePicker::make('tanggal_pengembalian')
                            ->label('Tanggal Pengembalian')
                            ->visible(fn(Forms\Get $get) => $get('status') === 'dikembalikan')
                            ->default(now()),

                    ]),

                Forms\Components\Section::make('Barang yang Dipinjam')
                    ->schema([
                        Forms\Components\Repeater::make('detail_peminjaman')
                            ->relationship('detailPeminjaman')
                            ->schema([
                                Forms\Components\Select::make('id_inventaris')
                                    ->relationship('inventaris', 'nama', fn(Builder $query) => $query->where('status', 'tersedia'))
                                    ->required()
                                    ->searchable()
                                    ->label('Nama Barang')
                                    ->disabled(fn($record) => $record !== null),

                                Forms\Components\TextInput::make('kuantitas')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->label('Jumlah')
                                    ->disabled(fn($record) => $record !== null),
                            ])
                            ->label('Detail Peminjaman')
                            ->required()
                            ->defaultItems(1)
                            ->disabled(fn($record) => $record !== null)
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Peminjam')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_peminjaman')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jangka')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => match ($state) {
                        'pendek' => 'success',
                        'panjang' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match ($state) {
                        'diajukan' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'dipinjam' => 'primary',
                        'jatuh tenggat' => 'danger',
                        'dikembalikan' => 'success',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'dipinjam' => 'Dipinjam',
                        'jatuh tenggat' => 'Jatuh Tenggat',
                        'dikembalikan' => 'Dikembalikan',
                    ])
            ])

            ->actions([
                // Action untuk melihat detail peminjaman
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalHeading(fn($record) => "Detail Peminjaman #{$record->id}")
                    ->modalContent(fn($record) => view('filament.resources.peminjaman.view', ['record' => $record])),

                // Action untuk setujui peminjaman
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'diajukan')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui peminjaman ini?')
                    ->action(function ($record) {
                        $record->status = 'disetujui';
                        $record->save();

                        Notification::make()
                            ->title('Peminjaman disetujui')
                            ->success()
                            ->send();
                    }),

                // Action untuk tolak peminjaman
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'diajukan')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = 'ditolak';
                        $record->catatan = $data['alasan_penolakan'];
                        $record->save();

                        Notification::make()
                            ->title('Peminjaman ditolak')
                            ->success()
                            ->send();
                    }),

                // Action untuk tandai sudah dipinjam
                Action::make('borrowed')
                    ->label('Tandai Dipinjam')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('primary')
                    ->visible(fn($record) => $record->status === 'disetujui')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->status = 'dipinjam';
                        $record->save();

                        Notification::make()
                            ->title('Status diperbarui: Dipinjam')
                            ->success()
                            ->send();
                    }),

                // Action untuk tandai dikembalikan
                Action::make('returned')
                    ->label('Tandai Dikembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->visible(fn($record) => in_array($record->status, ['dipinjam', 'jatuh tenggat']))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_pengembalian')
                            ->label('Tanggal Pengembalian')
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->placeholder('Tambahkan catatan jika ada'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = 'dikembalikan';
                        $record->tanggal_pengembalian = $data['tanggal_pengembalian'];
                        if (!empty($data['catatan'])) {
                            $record->catatan = $data['catatan'];
                        }
                        $record->save();

                        Notification::make()
                            ->title('Barang berhasil dikembalikan')
                            ->success()
                            ->send();
                    }),

                // Action untuk edit
                Tables\Actions\EditAction::make(),

            ])

            ->headerActions([
                Action::make('exportAll')
                    ->label('Export All Data')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return Excel::download(new ExportAllData, 'all_data.xlsx');
                    }),
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
            'index' => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}