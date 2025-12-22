<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriterionResource\Pages;
use App\Models\Criterion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CriterionResource extends Resource
{
    protected static ?string $model = Criterion::class;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Data Bobot (CF)'; // Label disesuaikan permintaan

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->disabled() // Kode sebaiknya tidak diubah agar logika Model aman
                    ->required(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('weight')
                    ->label('Bobot (%)')
                    ->numeric()
                    ->suffix('%')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Kode'),
                Tables\Columns\TextColumn::make('name')->label('Kriteria'),
                Tables\Columns\TextColumn::make('weight')->label('Bobot (%)'),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->paginated(false); // Matikan pagination karena data sedikit
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCriteria::route('/'),
            'edit' => Pages\EditCriterion::route('/{record}/edit'),
        ];
    }
}
