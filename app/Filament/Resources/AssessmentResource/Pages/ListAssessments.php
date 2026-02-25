<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAssessments extends ListRecords
{
    protected static string $resource = AssessmentResource::class;

    public function mount(): void
    {
        parent::mount();

        // LOGIKA PEMBERITAHUAN
        // Cek apakah ada jadwal yang statusnya 'Aktif' TAPI tanggalnya sudah lewat (Expired)
        $expiredSchedules = Schedule::where('is_active', true)
            ->where('end_date', '<', now()->format('Y-m-d'))
            ->get();

        if ($expiredSchedules->isNotEmpty()) {
            foreach ($expiredSchedules as $schedule) {
                // Kirim notifikasi peringatan yang persisten (harus di-close manual)
                Notification::make()
                    ->warning() // Warna Kuning/Oranye
                    ->title('Periode Penilaian Berakhir')
                    ->body("Periode <strong>{$schedule->name}</strong> telah melewati tanggal selesai ({$schedule->end_date}). <br>Sistem otomatis menganggapnya tidak aktif.")
                    ->persistent() // Agar tidak hilang sendiri
                    ->send();
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            // Tombol "New Assessment"
            Actions\CreateAction::make()
                // Fitur: DISABLE tombol jika TIDAK ADA jadwal yang aktif
                ->disabled(fn() => !Schedule::active()->exists())

                // Tambahkan tooltip (penjelasan saat mouse diarahkan ke tombol yang mati)
                ->tooltip(fn() => !Schedule::active()->exists()
                    ? 'Tombol terkunci karena tidak ada Jadwal Penilaian yang sedang aktif.'
                    : 'Buat Penilaian Baru'),
        ];
    }
}
