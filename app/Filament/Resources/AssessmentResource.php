<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Record Penilaian'; // Sesuai Use Case

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penilaian')
                    ->schema([
                        // Pilih Jadwal (Use Case Mengelola Jadwal)
                        Forms\Components\Select::make('schedule_id')
                            ->relationship('schedule', 'name', fn($query) => $query->where('is_active', true))
                            ->label('Periode Penilaian')
                            ->required()
                            ->default(fn() => \App\Models\Schedule::where('is_active', true)->latest()->first()?->id),

                        // Pilih Karyawan
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
                        Forms\Components\TextInput::make('c1_capacity_plan')
                            ->label('C1 - Capacity Plan')
                            ->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c2_kedisiplinan')
                            ->label('C2 - Kedisiplinan')
                            ->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c3_pengetahuan')
                            ->label('C3 - Pengetahuan')
                            ->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c4_loyalitas')
                            ->label('C4 - Loyalitas')
                            ->numeric()->minValue(0)->maxValue(100)->required(),
                        Forms\Components\TextInput::make('c5_team_work')
                            ->label('C5 - Team Work')
                            ->numeric()->minValue(0)->maxValue(100)->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.name')
                    ->label('Periode')
                    ->sortable()
                    ->badge(), // Tampilan badge agar menarik
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
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
            ->defaultSort('final_score', 'desc') // Otomatis Ranking
            ->filters([
                // Fitur Laporan: Filter berdasarkan Periode Jadwal
                SelectFilter::make('schedule_id')
                    ->relationship('schedule', 'name')
                    ->label('Filter Periode'),
            ])
            ->actions([
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
        ];
    }
}
