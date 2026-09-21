<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Modul - Pusat Kawalan LZNK</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .preload * { transition: none !important; }
        
        :root {
            --bg-1: #0f2818; --bg-2: #1a3a2a; --text-main: #ffffff; --text-muted: #a8d5ba;
            --text-gold: #f0d56f; --text-gold-dark: #d4af37;
            --glass-bg: rgba(10, 40, 25, 0.7); --glass-card: rgba(10, 40, 25, 0.6);
            --glass-border: rgba(212, 175, 55, 0.3); --glass-border-hover: rgba(212, 175, 55, 0.6);
            --glass-shadow: rgba(212, 175, 55, 0.15); --particle: rgba(212, 175, 55, 0.15);
        }

        body.light-mode {
            --bg-1: #f2fcf5; --bg-2: #e6f7ec; --text-main: #064e3b; --text-muted: #047857;
            --text-gold: #b45309; --text-gold-dark: #92400e;
            --glass-bg: rgba(255, 255, 255, 0.85); --glass-card: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(16, 185, 129, 0.4); --glass-border-hover: rgba(16, 185, 129, 0.8);
            --glass-shadow: rgba(16, 185, 129, 0.2); --particle: rgba(16, 185, 129, 0.25);
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); color: var(--text-main); background-attachment: fixed; transition: background 0.5s ease, color 0.5s ease; }
        .theme-text-main { color: var(--text-main); transition: color 0.5s ease; }
        .theme-text-muted { color: var(--text-muted); transition: color 0.5s ease; }
        .theme-text-gold { color: var(--text-gold); transition: color 0.5s ease; }
        .theme-border { border-color: var(--glass-border); transition: border-color 0.5s ease; }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease; }
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        .glass-card:hover { border-color: var(--glass-border-hover); box-shadow: 0 10px 30px var(--glass-shadow); transform: translateY(-3px); }

        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); }
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: rgba(212, 175, 55, 0.1); transition: all 0.3s; }
        .btn-outline-gold:hover { background: rgba(212, 175, 55, 0.2); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); }
        body.light-mode .btn-outline-gold { border-color: #10b981; color: #047857; background: rgba(16, 185, 129, 0.1); }
        body.light-mode .btn-outline-gold:hover { background: rgba(16, 185, 129, 0.2); box-shadow: 0 0 15px rgba(16, 185, 129, 0.3); color: #064e3b; }
    </style>
</head>
<body class="relative preload">
    <script>if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');</script>
    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l h-10 pl-4 theme-border">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Urus Modul</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase translation-target">Pengurusan Modul Bercabang</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-black/20 p-1 rounded-lg border theme-border mr-1 backdrop-blur-sm shadow-inner z-50 relative">
                        <button onclick="switchLanguage('ms')" id="lang-ms" class="px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all">BM</button>
                        <button onclick="switchLanguage('en')" id="lang-en" class="px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all">EN</button>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer mr-2">
                        <input type="checkbox" id="theme-toggle" class="sr-only peer" autocomplete="off">
                        <div class="w-12 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-[#10b981] transition-colors duration-300 relative border border-gray-500 peer-checked:border-green-400">
                            <div class="absolute top-[1px] left-[2px] bg-white rounded-full h-5 w-5 transition-transform duration-300 peer-checked:translate-x-full flex items-center justify-center shadow-sm" id="toggle-circle">
                                <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </div>
                    </label>
                    <a href="{{ route('admin.builder.index') }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden md:inline translation-target">Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        <div class="flex justify-between items-end mb-8 border-b theme-border pb-4">
            <div>
                <h2 class="text-3xl font-bold text-gold-gradient mb-2 translation-target">Senarai Bilik (Modul)</h2>
                <p class="theme-text-muted translation-target">Urus dan selenggara modul kesedaran siber staf LZNK.</p>
            </div>
            <!-- UPDATED BUTTON LINK HERE -->
            <a href="{{ route('admin.builder.room.create') }}" class="btn-gold px-5 py-2.5 rounded-lg text-sm font-bold flex items-center gap-2 tracking-wide uppercase">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                <span class="translation-target">Tambah Bilik Baru</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg flex items-center gap-3 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
                <div class="glass-card p-6 flex flex-col group relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <div class="theme-icon-bg p-2 rounded-lg border theme-border">
                            <svg class="w-6 h-6 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="px-3 py-1 text-[10px] font-black rounded-full tracking-widest uppercase {{ $room->is_active ? 'bg-green-500/20 text-green-500 border border-green-500/50' : 'bg-red-500/20 text-red-500 border border-red-500/50' }}">
                            {{ $room->is_active ? '• AKTIF' : '• TIDAK AKTIF' }}
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold theme-text-main leading-tight mb-3 db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</h3>
                    <p class="theme-text-muted text-sm mb-6 flex-1">{{ $room->description ?? 'Tiada deskripsi disediakan.' }}</p>
                    
                    <div class="flex items-center gap-2 mb-6 text-sm font-bold theme-text-gold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $room->questions()->count() }} <span class="translation-target">Soalan Branching</span></span>
                    </div>
                    
                    <!-- BUTANG TINDAKAN (Urus, Edit, Delete) -->
                    <div class="flex items-center gap-2 mb-3">
                        <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold text-center font-bold py-2.5 px-4 rounded-lg uppercase tracking-widest text-sm flex-1 translation-target">
                            Urus Soalan
                        </a>
                        
                        <a href="{{ route('admin.builder.room.edit', $room->id) }}" class="btn-outline-gold p-2.5 rounded-lg transition flex-shrink-0" title="Edit Modul">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        
                        <form action="{{ route('admin.builder.room.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Adakah anda pasti mahu memadam modul ini? Semua soalan di dalamnya akan terpadam!');" class="m-0 flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500/10 border border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white p-2.5 rounded-lg transition" title="Padam Modul">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>

                    <!-- SIMULASI BUTANG -->
                    <a href="{{ route('agent.play.room', $room->id) }}" target="_blank" class="w-full text-center border theme-border theme-text-main opacity-70 hover:opacity-100 hover:bg-white/10 font-bold py-2.5 rounded-lg transition text-xs tracking-widest uppercase flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="translation-target">Simulasi Modul</span>
                    </a>
                </div>
            @endforeach
            
            @if($rooms->count() == 0)
                <div class="col-span-full glass-card py-20 flex flex-col items-center justify-center border-dashed border-2 theme-border">
                    <p class="theme-text-gold font-bold text-xl mb-2 translation-target">Tiada Modul Dijumpai</p>
                    <p class="theme-text-muted text-sm translation-target">Klik "Tambah Modul" untuk bermula.</p>
                </div>
            @endif
        </div>
    </main>

    <script>
        const container = document.getElementById('particlesContainer');
        for (let i = 0; i < 40; i++) {
            const particle = document.createElement('div'); particle.className = 'particle';
            const size = Math.random() * 4 + 2; particle.style.width = size + 'px'; particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%'; particle.style.animationDuration = (Math.random() * 15 + 10) + 's';
            particle.style.animationDelay = Math.random() * 5 + 's'; container.appendChild(particle);
        }

        const themeToggle = document.getElementById('theme-toggle');
        const toggleCircle = document.getElementById('toggle-circle');
        const body = document.body;
        const moonIcon = '<svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>';
        const sunIcon = '<svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>';

        if (body.classList.contains('light-mode')) { themeToggle.checked = true; toggleCircle.innerHTML = sunIcon; } 
        else { toggleCircle.innerHTML = moonIcon; }

        themeToggle.addEventListener('change', () => {
            if (themeToggle.checked) { body.classList.add('light-mode'); localStorage.setItem('theme', 'light'); toggleCircle.innerHTML = sunIcon; } 
            else { body.classList.remove('light-mode'); localStorage.setItem('theme', 'dark'); toggleCircle.innerHTML = moonIcon; }
        });

        const enDictionary = {
            "Urus Modul": "Manage Modules",
            "Pengurusan Modul Bercabang": "Branching Module Management",
            "Kembali ke Dashboard": "Back to Dashboard",
            "Senarai Bilik (Modul)": "Room List (Modules)",
            "Urus dan selenggara modul kesedaran siber staf LZNK.": "Manage and maintain staff cyber awareness modules.",
            "Tambah Bilik Baru": "Add New Room",
            "Soalan Branching": "Branching Questions",
            "Urus Soalan": "Manage Questions",
            "Simulasi Modul": "Simulate Module",
            "Tiada Modul Dijumpai": "No Modules Found",
            "Klik \"Tambah Modul\" untuk bermula.": "Click \"Add Module\" to start.",
            "Tambah Modul Baru": "Add New Module",
            "Tajuk Modul": "Module Title",
            "Markah Lulus": "Passing Mark",
            "Batal": "Cancel",
            "Simpan": "Save"
        };
        const currentLang = localStorage.getItem('lang') || 'ms';

        function applyTranslation() {
            const btnEn = document.getElementById('lang-en'); const btnMs = document.getElementById('lang-ms');
            if (currentLang === 'en') {
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm";
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                document.querySelectorAll('.translation-target').forEach(el => { const text = el.textContent.trim(); if (text && enDictionary[text]) el.textContent = enDictionary[text]; });
                document.querySelectorAll('.db-translate').forEach(el => { el.textContent = el.getAttribute('data-en') || el.getAttribute('data-ms'); });
            } else {
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm";
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                document.querySelectorAll('.db-translate').forEach(el => { el.textContent = el.getAttribute('data-ms'); });
            }
        }
        function switchLanguage(lang) { localStorage.setItem('lang', lang); window.location.reload(); }
        document.addEventListener('DOMContentLoaded', () => { applyTranslation(); setTimeout(() => document.body.classList.remove('preload'), 100); });
    </script>
    @include('partials.cursor')
</body>
</html>