<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToiPlanetController;

// Mengarahkan halaman utama (/) ke fungsi index di ToiPlanetController
Route::get('/', [ToiPlanetController::class, 'index']);

// Mendaftarkan rute otomatis untuk semua fungsi CRUD (Tambah, Edit, Hapus)
Route::resource('catalog', ToiPlanetController::class);