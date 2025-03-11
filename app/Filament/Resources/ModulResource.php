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
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule as ValidationRule;

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
                    ->required()
                    ->unique(Modul::class, 'modul_ke', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                        $rule->where('id_praktikums', request('id_praktikums'));
                    })
                    ->rules([
                        fn($get) => ValidationRule::unique('moduls', 'modul_ke')
                            ->where('id_praktikums', $get('id_praktikums'))
                            ->ignore($get('id')),
                    ]),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Judul modul'),
                Forms\Components\Textarea::make('deskripsi'),
                Forms\Components\FileUpload::make('file_path')
                    ->disk('public')
                    ->downloadable()
                    ->Label('Upload modul'),
                Forms\Components\TextInput::make('link_video')
                    ->Label('Link Video'),
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