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
}
