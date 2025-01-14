<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarisResource\Pages;
use App\Filament\Resources\InventarisResource\RelationManagers;
use App\Models\Inventaris;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarisResource extends Resource
{
    protected static ?string $model = Inventaris::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventaris dan Peminjaman';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Inventaris')
                    ->required(),

                Forms\Components\TextInput::make('kuantitas')
                    ->label('Kuantitas')
                    ->numeric()
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->nullable(),

                Forms\Components\FileUpload::make('images')
                    ->disk('public')
                    ->label('Foto')
                    ->image()
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'tidak tersedia' => 'Tidak Tersedia',
                    ])
                    ->default('tersedia')
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('deskripsi'),
                Tables\Columns\ImageColumn::make('images'),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'primary',
                        'tidak tersedia' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'tersedia' => 'heroicon-m-check-badge',
                        'tidak tersedia' => 'heroicon-m-exclamation-triangle',
                    })
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state);   
                    })
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
            'index' => Pages\ListInventaris::route('/'),
            'create' => Pages\CreateInventaris::route('/create'),
            'edit' => Pages\EditInventaris::route('/{record}/edit'),
        ];
    }
}
