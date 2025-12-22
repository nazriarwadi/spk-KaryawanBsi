<?php

use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama ('/') langsung ke '/admin'
Route::redirect('/', '/admin');
