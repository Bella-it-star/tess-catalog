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
        
        <!-- Header (Judul & Tombol Sync) -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <span class="text-indigo-400">🌌</span> Ariel TESS Candidates
            </h1>
            <button class="border-2 border-cyan-400 text-cyan-400 px-4 py-2 rounded-lg font-semibold shadow-[0_0_15px_rgba(34,211,238,0.4)] hover:bg-cyan-950 transition">
                ⚡ Sync API ExoFOP
            </button>
        </div>

        <!-- Sub-judul & Tombol Tambah Manual -->
        <div class="flex justify-between items-end mb-4">
            <div>
                <h2 class="text-2xl font-semibold text-white mb-1">Katalog Target Exoplanet</h2>
                <p class="text-slate-400 text-sm">Database observasi kandidat planet TESS</p>
            </div>
            <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2 rounded-lg transition shadow-lg shadow-indigo-600/30">
                + Tambah Target Manual
            </button>
        </div>

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
                        <td class="p-4 text-cyan-400 font-mono font-semibold">{{ $planet->star->tic_id }}</td>
                        <td class="p-4 text-orange-400 font-mono font-semibold">{{ $planet->toi_number }}</td>
                        <td class="p-4 text-center">
                            <span class="bg-indigo-600 px-3 py-1 rounded text-white font-bold">{{ $planet->master_priority }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="bg-blue-600 px-3 py-1 rounded text-white font-bold">{{ $planet->sg1b_priority }}</span>
                        </td>
                        <td class="p-4">{{ $planet->tess_notes }}</td>
                        <td class="p-4">{{ $planet->personal_notes }}</td>
                        <td class="p-4 flex gap-2 justify-center">
                            <button class="border border-yellow-500 text-yellow-500 px-3 py-1 rounded-md text-xs hover:bg-yellow-500/20 transition">Edit</button>
                            <button class="border border-red-500 text-red-500 px-3 py-1 rounded-md text-xs hover:bg-red-500/20 transition">Hapus</button>
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

    </div>
</body>
</html>