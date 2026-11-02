<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Target Exoplanet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: radial-gradient(circle at top, #1a0b2e 0%, #080310 100%); color: #e2e8f0; font-family: 'Segoe UI', sans-serif; min-height: 100vh; }
        .glass-panel { background: rgba(30, 20, 60, 0.7); border: 1px solid rgba(139, 92, 246, 0.4); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="p-6 md:p-10">
    <div class="max-w-2xl mx-auto glass-panel p-8 rounded-2xl shadow-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">➕ Tambah Target Exoplanet</h2>
        
        <form action="{{ route('planets.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-indigo-300 mb-1">Pilih Bintang Induk (TIC ID)</label>
                <select name="star_id" class="w-full bg-indigo-950 border border-indigo-500 rounded p-2 text-cyan-300 font-mono" required>
                    @foreach($stars as $star)
                        <option value="{{ $star->id }}">{{ $star->tic_id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-indigo-300 mb-1">Nomor TOI</label>
                <input type="text" name="toi_number" placeholder="Contoh: 155.01" class="w-full bg-indigo-950 border border-indigo-500 rounded p-2 text-orange-400 font-mono" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-indigo-300 mb-1">Master Priority</label>
                    <input type="number" name="master_priority" class="w-full bg-indigo-950 border border-indigo-500 rounded p-2 text-white" required>
                </div>
                <div>
                    <label class="block text-indigo-300 mb-1">SG1b Priority</label>
                    <input type="number" name="sg1b_priority" class="w-full bg-indigo-950 border border-indigo-500 rounded p-2 text-white" required>
                </div>
            </div>
            <div>
                <label class="block text-indigo-300 mb-1">Catatan TESS</label>
                <textarea name="tess_notes" class="w-full bg-indigo-950 border border-indigo-500 rounded p-2 text-white"></textarea>
            </div>
            <div>
                <label class="block text-indigo-300 mb-1">Catatan Pribadi</label>
                <textarea name="personal_notes" class="w-full bg-indigo-950 border border-indigo-500 rounded p-2 text-white"></textarea>
            </div>
            <div class="flex justify-between pt-4">
                <a href="{{ route('planets.index') }}" class="text-slate-400 hover:underline">Kembali</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2 rounded-lg font-bold">Simpan Target</button>
            </div>
        </form>
    </div>
</body>
</html>