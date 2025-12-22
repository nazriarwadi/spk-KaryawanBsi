<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\Employee;
use App\Models\Schedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Mengatur agar widget ini muncul paling atas
    protected static ?int $sort = 1;

    // PERBAIKAN: Tambahkan kata 'static' di sini
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Hitung Total Karyawan
        $totalKaryawan = Employee::count();

        // 2. Ambil Jadwal Aktif
        $activeSchedule = Schedule::where('is_active', true)->first();
        $periodeLabel = $activeSchedule ? $activeSchedule->name : 'Tidak ada periode aktif';

        // 3. Hitung Penilaian di Periode Aktif
        $totalPenilaian = $activeSchedule
            ? Assessment::where('schedule_id', $activeSchedule->id)->count()
            : 0;

        // 4. Cari Skor Tertinggi (Top 1)
        // Pastikan relasi 'employee' dimuat untuk menghindari N+1 problem jika perlu,
        // tapi untuk single row cukup seperti ini.
        $topEmployee = Assessment::orderBy('final_score', 'desc')->first();

        $topScore = $topEmployee ? number_format($topEmployee->final_score, 3) : '0';
        $topName = $topEmployee ? $topEmployee->employee->name : '-';

        return [
            Stat::make('Total Karyawan', $totalKaryawan)
                ->description('Data pegawai terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'), // Mengikuti warna BSI (Teal)

            Stat::make('Penilaian Masuk', $totalPenilaian)
                ->description($periodeLabel)
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'), // Hijau

            Stat::make('Peringkat #1 Saat Ini', $topScore)
                ->description($topName) // Menampilkan nama karyawan terbaik
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'), // Kuning/Emas
        ];
    }
}
