<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;

class EmployeePerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Karyawan Terbaik (SMART)';

    // Mengatur urutan agar muncul di bawah widget Statistik
    protected static ?int $sort = 2;

    // Membuat grafik lebar memenuhi layar
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Mengambil 5 data penilaian tertinggi dari jadwal aktif (jika ada)
        // Atau ambil global jika jadwal belum diatur
        $data = Assessment::with('employee')
            ->orderBy('final_score', 'desc')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Nilai Akhir',
                    'data' => $data->pluck('final_score'), // Data Angka (Y-Axis)
                    'backgroundColor' => '#00A39E', // Warna Teal BSI
                    'borderColor' => '#008c87',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('employee.name'), // Label Nama (X-Axis)
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
