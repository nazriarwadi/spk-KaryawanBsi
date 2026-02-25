<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Jadwal Penilaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Periode')
                        ->placeholder('Contoh: Penilaian Q1 2025')
                        ->required(),
                    Forms\Components\DatePicker::make('start_date')
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->required()
                        ->helperText('Jika tanggal ini terlewati, jadwal otomatis dianggap tidak aktif.'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText('Matikan manual jika ingin menutup jadwal lebih awal.'),
                ])
                    // Fitur Tambahan: Form jadi Read-Only jika sedang melihat data yang expired
                    ->disabled(fn(?Schedule $record) => $record && $record->isExpired()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->color(fn(Schedule $record) => $record->isExpired() ? 'danger' : null), // Merah jika expired

                // Logika: Otomatis terlihat "Silang" jika tanggal sudah lewat
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->getStateUsing(function (Schedule $record) {
                        // Jika Expired, paksa return FALSE (biar iconnya silang)
                        if ($record->isExpired()) {
                            return false;
                        }
                        // Jika tidak, kembalikan nilai asli dari database
                        return $record->is_active;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    // SYARAT PENTING: Sembunyikan tombol Edit jika sudah expired
                    ->hidden(fn(Schedule $record) => $record->isExpired())
                    ->tooltip('Jadwal yang sudah lewat tidak bisa diedit.'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
