<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Filament\Resources\PeminjamanResource\RelationManagers;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\Select::make('id_users')
                    ->relationship('user', 'name')
                    ->label('Nama peminjam')
                    ->required(),

                Forms\Components\Select::make('id_inventaris')
                    ->relationship('inventaris', 'nama', fn(Builder $query) => $query->where('status', 'tersedia'))
                    ->required()
                    ->label('Nama alat'),

                Forms\Components\TextInput::make('kuantitas')
                    ->numeric()
                    ->required()
                    ->label('Jumlah'),

                Forms\Components\DatePicker::make('tanggal_peminjaman')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'dipinjam' => 'Dipinjam',
                        'jatuh tenggat' => 'Jatuh Tenggat',
                        'dikembalikan' => 'Dikembalikan',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('inventaris.nama'),
                Tables\Columns\TextColumn::make('detail_peminjaman.kuantitas'),
                Tables\Columns\TextColumn::make('tanggal_peminjaman'),
                Tables\Columns\TextColumn::make('tanggal_selesai'),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match ($state) {
                        'diajukan' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'dipinjam' => 'primary',
                        'jatuh tenggat' => 'warning',
                        'dikembalikan' => 'success',
                    })
                    ->badge()
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
            'index' => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
