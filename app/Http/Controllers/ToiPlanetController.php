<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToiPlanet;
use App\Models\Star;

class ToiPlanetController extends Controller
{
    public function index()
    {
        // Ambil semua planet beserta data bintang induknya
        $planets = ToiPlanet::with('star')->get();
        
        // Kirim variabel $planets ke file tampilan 'catalog.index'
        return view('catalog.index', compact('planets'));
    }

    // Method create, store, edit, update, destroy biarkan ada (akan kita isi nanti untuk tombol Tambah, Edit, Hapus)
}