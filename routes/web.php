<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToiPlanetController;

// Mengarahkan halaman utama (/) langsung ke daftar katalog planets
Route::get('/', function () {
    return redirect()->route('planets.index');
});

Route::post('/planets/import', [App\Http\Controllers\ToiPlanetController::class, 'importCsv'])->name('planets.import');


// Mendaftarkan rute otomatis untuk semua fungsi CRUD
Route::resource('planets', ToiPlanetController::class);