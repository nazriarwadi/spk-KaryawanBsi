<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists; // PENTING: Import Infolist
use Filament\Infolists\Infolist; // PENTING
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Record Penilaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penilaian')
                    ->schema([
                        Forms\Components\Select::make('schedule_id')
                            ->relationship('schedule', 'name', fn($query) => $query->where('is_active', true))
                            ->label('Periode Penilaian')
                            ->required()
                            ->default(fn() => \App\Models\Schedule::where('is_active', true)->latest()->first()?->id),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->label('Nama Karyawan')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Input Kriteria Penilaian (Metode SMART)')
                    ->description('Masukkan nilai 0 sampai 100.')
                    ->schema([
                        Forms\Components\TextInput::make('c1_capacity_plan')->label('C1 - Capacity Plan')->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c2_kedisiplinan')->label('C2 - Kedisiplinan')->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c3_pengetahuan')->label('C3 - Pengetahuan')->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c4_loyalitas')->label('C4 - Loyalitas')->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c5_team_work')->label('C5 - Team Work')->numeric()->minValue(0)->maxValue(100)->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.name')->label('Periode')->sortable()->badge(),
                Tables\Columns\TextColumn::make('employee.name')->label('Nama Karyawan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('final_score')
                    ->label('Nilai Akhir (SMART)')
                    ->sortable()
                    ->weight('bold')
                    ->color(fn(string $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 70 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Input')->date(),
            ])
            ->defaultSort('final_score', 'desc')
            ->filters([
                SelectFilter::make('schedule_id')->relationship('schedule', 'name')->label('Filter Periode'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // PENTING: Tombol Mata (Lihat Detail)
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // --- BAGIAN BARU: TAMPILAN DETAIL (INFOLIST) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Hasil Penilaian Kinerja')
                    ->schema([
                        Infolists\Components\TextEntry::make('schedule.name')->label('Periode'),
                        Infolists\Components\TextEntry::make('employee.name')->label('Nama Karyawan'),
                        Infolists\Components\TextEntry::make('employee.nip')->label('NIP'),
                        Infolists\Components\TextEntry::make('created_at')->label('Tanggal Penilaian')->date(),
                    ])->columns(2),

                Infolists\Components\Section::make('Rincian Skor Kriteria')
                    ->schema([
                        Infolists\Components\TextEntry::make('c1_capacity_plan')->label('C1 - Capacity Plan'),
                        Infolists\Components\TextEntry::make('c2_kedisiplinan')->label('C2 - Kedisiplinan'),
                        Infolists\Components\TextEntry::make('c3_pengetahuan')->label('C3 - Pengetahuan'),
                        Infolists\Components\TextEntry::make('c4_loyalitas')->label('C4 - Loyalitas'),
                        Infolists\Components\TextEntry::make('c5_team_work')->label('C5 - Team Work'),
                    ])->columns(5),

                Infolists\Components\Section::make('Total Skor Akhir')
                    ->schema([
                        Infolists\Components\TextEntry::make('final_score')
                            ->label('SKOR SMART')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('primary'),
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
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
            'view' => Pages\ViewAssessment::route('/{record}'), // PENTING: Route View
        ];
    }
}
