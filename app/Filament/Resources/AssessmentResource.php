<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\FontWeight;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Record Penilaian';
    protected static ?string $navigationGroup = 'Manajemen Penilaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Penilaian')
                    ->description('Pilih periode jadwal dan karyawan yang akan dinilai.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('schedule_id')
                            ->relationship('schedule', 'name', fn($query) => $query->where('is_active', true))
                            ->label('Periode Penilaian')
                            ->required()
                            ->default(fn() => Schedule::where('is_active', true)->latest()->first()?->id),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->label('Nama Karyawan')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Input Nilai Kriteria (Data Mentah)')
                    ->description('Masukkan nilai asli (0-100). Sistem akan menghitung normalisasi secara otomatis (Vertikal).')
                    ->icon('heroicon-o-calculator')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('c1_capacity_plan')->label('C1 - Capacity Plan')->numeric()->minValue(0)->maxValue(100)->required()->suffix('Poin'),
                                Forms\Components\TextInput::make('c2_kedisiplinan')->label('C2 - Kedisiplinan')->numeric()->minValue(0)->maxValue(100)->required()->suffix('Poin'),
                                Forms\Components\TextInput::make('c3_pengetahuan')->label('C3 - Pengetahuan')->numeric()->minValue(0)->maxValue(100)->required()->suffix('Poin'),
                                Forms\Components\TextInput::make('c4_loyalitas')->label('C4 - Loyalitas')->numeric()->minValue(0)->maxValue(100)->required()->suffix('Poin'),
                                Forms\Components\TextInput::make('c5_team_work')->label('C5 - Team Work')->numeric()->minValue(0)->maxValue(100)->required()->suffix('Poin'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.name')->label('Periode')->sortable()->badge()->color('gray'),
                Tables\Columns\TextColumn::make('employee.name')->label('Nama Karyawan')->searchable()->sortable()->weight(FontWeight::Bold),

                // Menampilkan Nilai Akhir dengan Format 3 Desimal (Contoh: 67.631)
                Tables\Columns\TextColumn::make('final_score')
                    ->label('Nilai Akhir')
                    ->sortable()
                    ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn(string $state): string => number_format((float) $state, 3))
                    ->color(fn(string $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Input')->date('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('final_score', 'desc')
            ->filters([
                SelectFilter::make('schedule_id')->relationship('schedule', 'name')->label('Filter Periode'),
            ])
            ->headerActions([
                Action::make('cetak_rekap')
                    ->label('Cetak Laporan Ranking (PDF)')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(route('assessment.rekap.pdf'))
                    ->openUrlInNewTab(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Grid::make(3)->schema([
                            Infolists\Components\Group::make([
                                Infolists\Components\TextEntry::make('employee.name')->label('Nama Karyawan')->weight(FontWeight::Bold)->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                Infolists\Components\TextEntry::make('employee.nip')->label('NIP')->icon('heroicon-m-identification'),
                                Infolists\Components\TextEntry::make('schedule.name')->label('Periode Penilaian')->badge(),
                            ])->columnSpan(2),
                            Infolists\Components\Group::make([
                                Infolists\Components\TextEntry::make('final_score')
                                    ->label('SKOR AKHIR')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Black)
                                    ->color('primary')
                                    ->formatStateUsing(fn(string $state): string => number_format((float) $state, 3)),
                            ])->columnSpan(1),
                        ]),
                    ]),
                Infolists\Components\Section::make('Rincian Nilai Input')
                    ->schema([
                        Infolists\Components\Grid::make(5)->schema([
                            Infolists\Components\TextEntry::make('c1_capacity_plan')->label('C1')->alignCenter()->weight(FontWeight::Bold)->color('gray'),
                            Infolists\Components\TextEntry::make('c2_kedisiplinan')->label('C2')->alignCenter()->weight(FontWeight::Bold)->color('gray'),
                            Infolists\Components\TextEntry::make('c3_pengetahuan')->label('C3')->alignCenter()->weight(FontWeight::Bold)->color('gray'),
                            Infolists\Components\TextEntry::make('c4_loyalitas')->label('C4')->alignCenter()->weight(FontWeight::Bold)->color('gray'),
                            Infolists\Components\TextEntry::make('c5_team_work')->label('C5')->alignCenter()->weight(FontWeight::Bold)->color('gray'),
                        ]),
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
            'view' => Pages\ViewAssessment::route('/{record}'),
        ];
    }
}
