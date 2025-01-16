<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PraktikumResource\Pages;
use App\Filament\Resources\PraktikumResource\RelationManagers;
use App\Models\Praktikum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PraktikumResource extends Resource
{
    protected static ?string $model = Praktikum::class;

    protected static ?string $navigationGroup = 'Praktikum';

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Praktikum';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ListPraktikums::route('/'),
            'create' => Pages\CreatePraktikum::route('/create'),
            'edit' => Pages\EditPraktikum::route('/{record}/edit'),
        ];
    }
}
