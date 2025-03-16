<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email', ignoreRecord: true),

                        Forms\Components\TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->maxLength(20)
                            ->unique(User::class, 'nim', ignoreRecord: true),

                        Forms\Components\TextInput::make('no_telp')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(15),

                        Forms\Components\Select::make('id_roles')
                            ->label('Role')
                            ->options(Role::query()->pluck('roles', 'id')->toArray())
                            ->required()
                            ->default(3),

                        Forms\Components\Toggle::make('is_asisten')
                            ->label('Status Asisten')
                            ->default(false),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->dehydrated(false)
                            ->requiredWith('password'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_telp')
                    ->label('Nomor Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role.roles')
                    ->label('Role')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_asisten')
                    ->label('Asisten')
                    ->boolean(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Email Terverifikasi')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_roles')
                    ->label('Filter Role')
                    ->options(Role::query()->pluck('roles', 'id')->toArray()),

                Tables\Filters\Filter::make('is_asisten')
                    ->label('Hanya Asisten')
                    ->query(fn($query) => $query->where('is_asisten', true)),

                Tables\Filters\Filter::make('email_verified')
                    ->label('Email Terverifikasi')
                    ->query(fn($query) => $query->whereNotNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($action, $record) {
                        if ($record->peminjaman()->count() > 0 || $record->artikel()->count() > 0) {
                            Notification::make()
                                ->title('Tidak dapat menghapus user')
                                ->body('User ini memiliki data peminjaman atau artikel terkait.')
                                ->danger()
                                ->send();

                            $action->cancel();
                        }
                    }),
                Tables\Actions\Action::make('resetPassword')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->confirmed(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->required(),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'password' => Hash::make($data['password']),
                        ]);

                        Notification::make()
                            ->title('Password berhasil direset')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
