<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Mengelola Role';
    protected static ?string $navigationGroup = 'Manajemen User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama User')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),

                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'hrd' => 'HRD',
                        'pimpinan' => 'Pimpinan',
                    ])
                    ->default('hrd')
                    ->required()
                    ->label('Role Akses'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(), // Tambahkan fitur pencarian

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                // --- BAGIAN BARU: MENAMPILKAN ROLE ---
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge() // Tampilan seperti lencana/tag
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',    // Merah
                        'hrd' => 'success',     // Hijau
                        'pimpinan' => 'info',   // Biru
                        default => 'gray',
                    })
                    ->sortable(),
                // -------------------------------------

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // --- BAGIAN BARU: TOMBOL DELETE ---
                Tables\Actions\DeleteAction::make(),
                // ----------------------------------
            ])
            ->bulkActions([
                // Fitur Hapus Massal (Centang banyak sekaligus)
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
