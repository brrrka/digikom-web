<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModulResource\Pages;
use App\Filament\Resources\ModulResource\RelationManagers;
use App\Models\Modul;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ModulResource extends Resource
{
    protected static ?string $model = Modul::class;

    protected static ?string $navigationGroup = 'Praktikum';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Modul Praktikum';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_praktikums')
                    ->relationship('praktikum', 'name', fn($query) => $query->orderBy('id'))
                    ->label('Nama praktikum')
                    ->required(),
                Forms\Components\TextInput::make('modul_ke')
                    ->numeric()
                    ->label('Modul ke')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Judul modul'),
                Forms\Components\Textarea::make('deskripsi'),
                Forms\Components\FileUpload::make('file_path')
                    ->disk('public') // Menentukan disk storage
                    ->required()
                    ->downloadable()
                    ->Label('Upload modul'),
                Forms\Components\FileUpload::make('images')
                    ->disk('public')
                    ->visibility('public')
                    ->image()
                    ->Label('Gambar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('praktikum.name')
                    ->label('Nama Praktikum')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modul_ke')
                    ->label('Modul ke')
                    ->numeric(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul modul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File PDF')
                    ->formatStateUsing(fn($state) => 'Download PDF')
                    ->url(fn($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('No description')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('images')
                    ->disk('public')
                    ->visibility('public')
                    ->label('Preview Gambar')
                    ->placeholder('No image')
                    ->size(50),
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
            'index' => Pages\ListModuls::route('/'),
            'create' => Pages\CreateModul::route('/create'),
            'edit' => Pages\EditModul::route('/{record}/edit'),
        ];
    }
}
