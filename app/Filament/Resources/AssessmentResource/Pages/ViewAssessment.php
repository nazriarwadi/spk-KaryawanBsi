<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssessment extends ViewRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Cetak PDF
            Actions\Action::make('cetak_pdf')
                ->label('Cetak Laporan PDF')
                ->icon('heroicon-o-printer')
                ->url(fn($record) => route('assessment.pdf', $record))
                ->openUrlInNewTab(), // Membuka di tab baru

            Actions\EditAction::make(),
        ];
    }
}
