<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

// Mengarahkan halaman utama ('/') langsung ke '/admin'
Route::redirect('/', '/admin');

// Route Download PDF (Pastikan user login jika perlu keamanan)
Route::get('/assessment/{record}/pdf', [PdfController::class, 'downloadAssessment'])->name('assessment.pdf');
