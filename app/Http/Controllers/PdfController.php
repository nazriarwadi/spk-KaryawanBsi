<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function downloadAssessment(Assessment $record)
    {
        // Load view yang tadi kita buat dan masukkan data $record
        $pdf = Pdf::loadView('pdf.assessment', compact('record'));

        // Download file dengan nama otomatis
        return $pdf->download('Laporan_Kinerja_' . $record->employee->name . '.pdf');
    }

    public function downloadRekap()
    {
        // Ambil semua data penilaian
        // Urutkan berdasarkan final_score TERBESAR ke terkecil (DESC)
        $assessments = Assessment::with(['employee', 'schedule'])
            ->orderBy('final_score', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.assessment_rekap', compact('assessments'));

        // Set ukuran kertas Landscape agar tabel muat
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Rekap_Perangkingan.pdf');
    }
}
