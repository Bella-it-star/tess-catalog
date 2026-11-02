<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Target Exoplanet</title>
    <!-- Menggunakan Tailwind CDN agar instan tanpa setup Node.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Tema background luar angkasa gelap */
        body { background-color: #080514; color: #e2e8f0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .glass-panel { 
            background: rgba(20, 15, 50, 0.6); 
            border: 1px solid rgba(99, 102, 241, 0.4); 
            backdrop-filter: blur(12px); 
        }
    </style>
</head>
<body class="p-4 md:p-10 min-h-screen">
    <div class="max-w-6xl mx-auto">
        
        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="mb-6 bg-emerald-900/60 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg shadow-lg">
            ✨ {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-6 bg-red-900/60 border border-red-500 text-red-200 px-4 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-900/60 border border-red-500 text-red-200 px-4 py-3 rounded-lg shadow-lg">
            @foreach($errors->all() as $e) <div>⚠️ {{ $e }}</div> @endforeach
        </div>
        @endif
        

        <!-- Header (Judul & Tombol Sync) -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <span class="text-indigo-400"></span> Ariel TESS Candidates
            </h1>
           <!-- Form Tombol Import CSV -->
                <form action="{{ route('planets.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf

                    <input type="file" name="csv_file" accept=".csv,.txt,text/plain" required
                        class="bg-indigo-950 border border-indigo-500 text-cyan-300 px-3 py-1.5 rounded-lg text-sm outline-none focus:border-cyan-400 file:mr-3 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-cyan-900 file:text-cyan-300 hover:file:bg-cyan-800 transition cursor-pointer">

                    <input type="number" name="limit" min="1" placeholder="Batas baris (kosong = semua)"
                        class="bg-indigo-950 border border-indigo-500 text-cyan-300 px-3 py-1.5 rounded-lg text-sm w-52 outline-none focus:border-cyan-400">

                    <button type="submit" class="border-2 border-cyan-400 text-cyan-400 px-4 py-2 rounded-lg font-semibold shadow-[0_0_15px_rgba(34,211,238,0.4)] hover:bg-cyan-950 transition cursor-pointer whitespace-nowrap">
                        Import CSV NASA
                    </button>
                </form>
        </div>

        <!-- Sub-judul & Tombol Tambah Manual -->
        <div class="flex justify-between items-end mb-4">
            <div>
                <h2 class="text-2xl font-semibold text-white mb-1">Katalog Target Exoplanet</h2>
                <p class="text-slate-400 text-sm">Database observasi kandidat planet TESS</p>
            </div>
            <!-- Diperbaiki: Ditambahkan href ke route planets.create -->
            <a href="{{ route('planets.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2 rounded-lg transition shadow-lg shadow-indigo-600/30">
                + Tambah Target Manual
            </a>
        </div>

        <!-- Pencarian & Filter -->
        <form method="GET" action="{{ route('planets.index') }}" class="glass-panel rounded-xl p-4 mb-4 flex flex-wrap items-center gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari TIC ID, TOI, atau catatan..."
                class="flex-1 min-w-[220px] bg-indigo-950 border border-indigo-500/60 text-slate-200 px-3 py-2 rounded-lg text-sm outline-none focus:border-cyan-400">

            <select name="master" class="bg-indigo-950 border border-indigo-500/60 text-slate-200 px-3 py-2 rounded-lg text-sm">
                <option value="">Master: semua</option>
                @foreach (range(1, 5) as $p)
                    <option value="{{ $p }}" @selected(request('master') == $p)>Master {{ $p }}</option>
                @endforeach
            </select>

            <select name="sg1b" class="bg-indigo-950 border border-indigo-500/60 text-slate-200 px-3 py-2 rounded-lg text-sm">
                <option value="">SG1b: semua</option>
                @foreach (range(1, 5) as $p)
                    <option value="{{ $p }}" @selected(request('sg1b') == $p)>SG1b {{ $p }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Cari</button>

            @if(request()->hasAny(['q', 'master', 'sg1b']))
                <a href="{{ route('planets.index') }}" class="text-slate-400 hover:text-white text-sm underline">Reset</a>
            @endif
        </form>

        <p class="text-slate-400 text-xs mb-2">
            {{ $planets->firstItem() ?? 0 }}–{{ $planets->lastItem() ?? 0 }} dari {{ $planets->total() }} target
        </p>

        <!-- Tabel Data -->
        <div class="glass-panel rounded-2xl overflow-hidden shadow-2xl">
            <table class="w-full text-left text-sm">
                <thead class="bg-indigo-950/60 border-b border-indigo-500/40 text-slate-300">
                    <tr>
                        <th class="p-4 font-medium">TIC ID</th>
                        <th class="p-4 font-medium">TOI</th>
                        <th class="p-4 text-center font-medium">Master Priority</th>
                        <th class="p-4 text-center font-medium">SG1b Priority</th>
                        <th class="p-4 font-medium">Catatan TESS</th>
                        <th class="p-4 font-medium">Catatan Pribadi</th>
                        <th class="p-4 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-500/20">
                    <!-- Looping data dari Controller -->
                    @forelse ($planets as $planet)
                    <tr class="hover:bg-indigo-900/30 transition">
                        <td class="p-4 text-cyan-400 font-mono font-semibold">{{ $planet->star ? $planet->star->tic_id : '-' }}</td>
                        <td class="p-4 text-orange-400 font-mono font-semibold">{{ $planet->toi_number }}</td>
                        <td class="p-4 text-center">
                            <span class="bg-indigo-600 px-3 py-1 rounded text-white font-bold">{{ $planet->master_priority }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="bg-blue-600 px-3 py-1 rounded text-white font-bold">{{ $planet->sg1b_priority }}</span>
                        </td>
                        <td class="p-4">{{ $planet->tess_notes }}</td>
                        <td class="p-4">{{ $planet->personal_notes }}</td>
                        
                        <!-- Diperbaiki: Kolom Aksi terhubung ke Route Edit & Delete -->
                        <td class="p-4 flex gap-2 justify-center">
                            <!-- Tombol Edit -->
                            <a href="{{ route('planets.edit', $planet->id) }}" class="border border-yellow-500 text-yellow-500 px-3 py-1 rounded-md text-xs hover:bg-yellow-500/20 transition">
                                Edit
                            </a>

                            <!-- Form Tombol Hapus -->
                            <form action="{{ route('planets.destroy', $planet->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus target {{ $planet->toi_number }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-red-500 text-red-500 px-3 py-1 rounded-md text-xs hover:bg-red-500/20 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <!-- Jika database masih kosong -->
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">
                            Belum ada data target TESS. Silakan isi datanya.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $planets->links() }}
        </div>

    </div>
</body>
</html>