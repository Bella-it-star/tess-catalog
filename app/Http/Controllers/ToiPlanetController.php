<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToiPlanet;
use App\Models\Star;
use Illuminate\Support\Facades\DB;

class ToiPlanetController extends Controller
{
    /**
     * READ: Menampilkan semua data kandidat exoplanet
     */
    public function index(Request $request)
{
    $query = ToiPlanet::with('star');

    if ($search = trim($request->input('q', ''))) {
        $query->where(function ($q) use ($search) {
            $q->where('toi_number', 'like', "%{$search}%")
              ->orWhere('tess_notes', 'like', "%{$search}%")
              ->orWhere('personal_notes', 'like', "%{$search}%")
              ->orWhereHas('star', fn ($s) => $s->where('tic_id', 'like', "%{$search}%"));
        });
    }

    if ($request->filled('master')) {
        $query->where('master_priority', $request->input('master'));
    }

    if ($request->filled('sg1b')) {
        $query->where('sg1b_priority', $request->input('sg1b'));
    }

    $planets = $query->orderBy('master_priority', 'desc')   // prioritas tinggi dulu
                     ->orderBy('toi_number')
                     ->paginate(50)
                     ->withQueryString();                    // filter tetap terbawa saat pindah halaman

    return view('catalog.index', compact('planets'));
}

    /**
     * CREATE: Menampilkan form tambah target exoplanet
     */
    public function create()
    {
        $stars = Star::all();
        return view('catalog.create', compact('stars'));
    }

    /**
     * STORE: Menyimpan data target baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'star_id'         => 'required|exists:stars,id',
            'toi_number'      => 'required|string',
            'master_priority' => 'required|integer',
            'sg1b_priority'   => 'required|integer',
            'tess_notes'      => 'nullable|string',
            'personal_notes'  => 'nullable|string',
        ]);

        ToiPlanet::create($request->all());

        return redirect()->route('planets.index')->with('success', 'Target Exoplanet berhasil ditambahkan!');
    }

    /**
     * EDIT: Menampilkan form edit data planet berdasarkan ID
     */
    public function edit($id)
    {
        $planet = ToiPlanet::findOrFail($id);
        $stars = Star::all();
        return view('catalog.edit', compact('planet', 'stars'));
    }

    /**
     * UPDATE: Memperbarui data planet di database
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'star_id'         => 'required|exists:stars,id',
            'toi_number'      => 'required|string',
            'master_priority' => 'required|integer',
            'sg1b_priority'   => 'required|integer',
            'tess_notes'      => 'nullable|string',
            'personal_notes'  => 'nullable|string',
        ]);

        $planet = ToiPlanet::findOrFail($id);
        $planet->update($request->all());

        return redirect()->route('planets.index')->with('success', 'Target Exoplanet berhasil diperbarui!');
    }

    /**
     * DESTROY: Menghapus data planet dari database
     */
    public function destroy($id)
    {
        $planet = ToiPlanet::findOrFail($id);
        $planet->delete();

        return redirect()->route('planets.index')->with('success', 'Target Exoplanet berhasil dihapus!');
    }


    /**
     * IMPORT CSV: Mengambil 5 data dari file CSV NASA lokal
     */
    public function importCsv(Request $request)
    {
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:30720', // 30 MB
    ]);

    set_time_limit(0);

    $lines = file($request->file('csv_file')->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $header = array_shift($lines);

    // Posisi kolom dibaca dari header (fixed-width)
    $pos = fn ($name) => strpos($header, $name);
    $ticPos    = 0;
    $toiPos    = $pos('TOI');
    $masterPos = $pos('Master');
    $sg1bPos   = $pos('SG1B');
    $notePos   = $pos('Comment');

    if ($toiPos === false || $masterPos === false || $sg1bPos === false) {
        return back()->with('error', 'Header file tidak dikenali. Pastikan ini file export ExoFOP TOI.');
    }

    $limit = (int) $request->input('limit', 0); // 0 = semua baris
    $count = 0;

    DB::transaction(function () use ($lines, $toiPos, $masterPos, $sg1bPos, $notePos, $limit, &$count) {
        foreach ($lines as $line) {
            if ($limit && $count >= $limit) break;

            $tic    = trim(substr($line, 0, $toiPos));
            $toi    = trim(substr($line, $toiPos, 20));
            $master = (int) trim(substr($line, $masterPos, 7));
            $sg1b   = (int) trim(substr($line, $sg1bPos, 7));
            $note   = $notePos !== false ? trim(substr($line, $notePos)) : '';

            if ($tic === '' || $toi === '') continue;

            $star = Star::firstOrCreate(
                ['tic_id' => $tic]
            );

            ToiPlanet::updateOrCreate(
                ['toi_number' => $toi],
                [
                    'star_id'         => $star->id,
                    'master_priority' => $master,
                    'sg1b_priority'   => $sg1b,
                    'tess_notes'      => $note !== '' ? $note : 'Imported from ExoFOP',
                    'personal_notes'  => 'Auto-imported target.',
                ]
            );
            $count++;
        }
    });

    if ($count === 0) {
        return redirect()->route('planets.index')->with('error', 'Tidak ada baris yang berhasil diimpor.');
    }

    return redirect()->route('planets.index')->with('success', "Berhasil mengimpor $count target exoplanet!");
    }
}