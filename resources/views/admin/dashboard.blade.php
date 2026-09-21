<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cyber Escape Room LZNK</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* 🛑 HALANG ANIMASI SEMASA PAGE RELOAD */
        .preload * {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -ms-transition: none !important;
            -o-transition: none !important;
            transition: none !important;
        }
        
        /* 🎨 SISTEM TEMA PINTAR */
        :root {
            --bg-1: #0f2818;
            --bg-2: #1a3a2a;
            --text-main: #ffffff;
            --text-muted: #a8d5ba;
            --text-gold: #f0d56f;
            --text-gold-dark: #d4af37;
            --glass-bg: rgba(10, 40, 25, 0.7);
            --glass-card: rgba(10, 40, 25, 0.6);
            --glass-border: rgba(212, 175, 55, 0.3);
            --glass-border-hover: rgba(212, 175, 55, 0.6);
            --glass-shadow: rgba(212, 175, 55, 0.15);
            --icon-bg: rgba(212, 175, 55, 0.2);
            --table-header: rgba(15, 40, 24, 0.8);
            --table-row-hover: rgba(212, 175, 55, 0.1);
            --particle: rgba(212, 175, 55, 0.15);
        }

        body.light-mode {
            --bg-1: #f2fcf5; 
            --bg-2: #e6f7ec; 
            --text-main: #064e3b; 
            --text-muted: #047857;
            --text-gold: #b45309; 
            --text-gold-dark: #92400e;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-card: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(16, 185, 129, 0.4);
            --glass-border-hover: rgba(16, 185, 129, 0.8);
            --glass-shadow: rgba(16, 185, 129, 0.2);
            --icon-bg: rgba(16, 185, 129, 0.2);
            --table-header: rgba(230, 247, 236, 0.9);
            --table-row-hover: rgba(16, 185, 129, 0.1);
            --particle: rgba(16, 185, 129, 0.25);
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); 
            color: var(--text-main); 
            background-attachment: fixed; 
            transition: background 0.5s ease, color 0.5s ease;
        }

        .theme-text-main { color: var(--text-main); transition: color 0.5s ease; }
        .theme-text-muted { color: var(--text-muted); transition: color 0.5s ease; }
        .theme-text-gold { color: var(--text-gold); transition: color 0.5s ease; }
        .theme-text-gold-dark { color: var(--text-gold-dark); transition: color 0.5s ease; }
        .theme-border { border-color: var(--glass-border); transition: border-color 0.5s ease; }
        .theme-icon-bg { background: var(--icon-bg); border-color: var(--glass-border); transition: all 0.5s ease; }

        /* Zarah Terapung */
        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease; }
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        /* Kaca Mewah */
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        .glass-card:hover { border-color: var(--glass-border-hover); box-shadow: 0 10px 30px var(--glass-shadow); transform: translateY(-5px); }

        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); }
        .btn-outline-gold { border: 1px solid #d4af37; color: #f0d56f; background: rgba(212, 175, 55, 0.1); transition: all 0.3s; }
        .btn-outline-gold:hover { background: rgba(212, 175, 55, 0.2); box-shadow: 0 0 15px rgba(212, 175, 55, 0.3); }
        body.light-mode .btn-outline-gold { border-color: #10b981; color: #047857; background: rgba(16, 185, 129, 0.1); }
        body.light-mode .btn-outline-gold:hover { background: rgba(16, 185, 129, 0.2); box-shadow: 0 0 15px rgba(16, 185, 129, 0.3); }
        
        .btn-logout { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.4); color: #ef4444; transition: all 0.3s; }
        .btn-logout:hover { background: rgba(239, 68, 68, 0.8); border-color: #ef4444; color: #fff; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }

        ::-webkit-scrollbar { height: 8px; width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(10, 40, 25, 0.5); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--glass-border-hover); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-gold); }

        /* Scrollbar khusus untuk Kotak Putih Modal */
        .modal-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
        .modal-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .modal-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .modal-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="relative preload">
    
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l h-10 pl-4 theme-border">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Pusat Kawalan Admin</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase translation-target">Pemantauan LZNK Cakna Siber</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="flex items-center bg-black/20 p-1 rounded-lg border theme-border mr-1 md:mr-2 backdrop-blur-sm shadow-inner z-50 relative">
                        <button onclick="switchLanguage('ms')" id="lang-ms" class="px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm">BM</button>
                        <button onclick="switchLanguage('en')" id="lang-en" class="px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all">EN</button>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer mr-2" title="Tukar Tema">
                        <input type="checkbox" id="theme-toggle" class="sr-only peer" autocomplete="off">
                        <div class="w-12 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-[#10b981] transition-colors duration-300 relative border border-gray-500 peer-checked:border-green-400">
                            <div class="absolute top-[1px] left-[2px] bg-white rounded-full h-5 w-5 transition-transform duration-300 peer-checked:translate-x-full flex items-center justify-center shadow-sm" id="toggle-circle">
                                <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </div>
                    </label>

                    <a href="{{ route('admin.builder.modules') }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span class="hidden md:inline translation-target">Urus Modul</span>
                    </a>
                    
                    <a href="{{ route('builder.export.hub') }}" class="btn-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm uppercase tracking-wider shadow-[0_0_15px_rgba(212,175,55,0.4)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span class="hidden md:inline translation-target">Eksport</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn-logout flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden md:inline translation-target">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        
        <div id="ajax-notification" class="hidden mb-6 px-4 py-3 rounded-lg flex items-center gap-3 font-semibold transition-opacity duration-500 opacity-0">
            <svg id="ajax-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span id="ajax-message" class="translation-target">Tindakan berjaya.</span>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded-lg flex items-center gap-3 font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg flex items-center gap-3 font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-6 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-black/5 text-9xl">👥</div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="theme-icon-bg p-3 rounded-xl border">
                        <svg class="w-6 h-6 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="text-sm font-bold theme-text-muted uppercase tracking-widest translation-target">Total Staf</span>
                </div>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h2 class="text-4xl font-black theme-text-main drop-shadow-md">{{ $totalStaff ?? 0 }}</h2>
                    <span class="text-xs font-bold text-green-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> 
                        <span class="translation-target">Aktif</span>
                    </span>
                </div>
            </div>

            <div class="glass-card p-6 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-black/5 text-9xl">🏆</div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="theme-icon-bg p-3 rounded-xl border">
                        <svg class="w-6 h-6 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <span class="text-sm font-bold theme-text-muted uppercase tracking-widest translation-target">Tertinggi</span>
                </div>
                <div class="relative z-10">
                    <h2 class="text-4xl font-black theme-text-gold drop-shadow-md">{{ $highestScore ?? 0 }}</h2>
                    <p class="text-xs theme-text-muted opacity-80 mt-1 uppercase font-bold tracking-wider translation-target">Rekod Terbaik</p>
                </div>
            </div>

            <div class="glass-card p-6 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-black/5 text-9xl">📊</div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="theme-icon-bg p-3 rounded-xl border">
                        <svg class="w-6 h-6 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <span class="text-sm font-bold theme-text-muted uppercase tracking-widest translation-target">Purata</span>
                </div>
                <div class="flex items-baseline gap-1 relative z-10">
                    <h2 class="text-4xl font-black theme-text-main drop-shadow-md">{{ $averageScore ?? 0 }}</h2>
                    <span class="text-sm theme-text-gold-dark font-bold">/ {{ $maxScore ?? 0 }}</span>
                </div>
            </div>

            <div class="glass-card p-6 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-black/5 text-9xl">⭐</div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="theme-icon-bg p-3 rounded-xl border">
                        <svg class="w-6 h-6 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-bold theme-text-muted uppercase tracking-widest translation-target">Tamat Misi</span>
                </div>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h2 class="text-4xl font-black theme-text-main drop-shadow-md">{{ $completedStaff ?? 0 }}</h2>
                    <span class="text-sm theme-text-gold-dark font-bold uppercase translation-target">staf</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="glass-card p-6 relative group" title="Klik pada graf untuk melihat penyumbang">
                <h3 class="text-lg font-bold text-gold-gradient mb-6 uppercase tracking-widest border-b theme-border pb-2 flex items-center gap-2">
                    <span class="translation-target">Taburan Markah Keseluruhan</span> <span class="text-[10px] bg-[#10b981]/20 text-[#10b981] px-2 py-0.5 rounded border border-[#10b981]/40 translation-target font-bold">KLIK GRAF</span>
                </h3>
                <div class="h-64 relative w-full">
                    <canvas id="scoreChart"></canvas>
                </div>
            </div>
            <div class="glass-card p-6 relative group" title="Klik pada graf untuk melihat penyumbang">
                <h3 class="text-lg font-bold text-gold-gradient mb-6 uppercase tracking-widest border-b theme-border pb-2 flex items-center gap-2">
                    <span class="translation-target">Kadar Kemajuan Modul</span> <span class="text-[10px] bg-[#10b981]/20 text-[#10b981] px-2 py-0.5 rounded border border-[#10b981]/40 translation-target font-bold">KLIK GRAF</span>
                </h3>
                <div class="h-64 relative w-full overflow-y-auto overflow-x-hidden modal-scrollbar pr-2">
                    <div class="w-full min-h-[600px]">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="glass-card p-6 relative group" title="Klik pada graf untuk melihat penyumbang">
                <h3 class="text-lg font-bold text-gold-gradient mb-6 uppercase tracking-widest border-b theme-border pb-2 flex items-center gap-2">
                    <span class="translation-target">Purata Markah Staf</span> <span class="text-[10px] bg-[#10b981]/20 text-[#10b981] px-2 py-0.5 rounded border border-[#10b981]/40 translation-target font-bold">KLIK GRAF</span>
                </h3>
                <div class="h-64 relative w-full overflow-x-auto overflow-y-hidden modal-scrollbar pb-2">
                    <div class="h-full min-w-[800px]">
                        <canvas id="averageChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 relative group" title="Klik pada graf untuk melihat penyumbang">
                <h3 class="text-lg font-bold text-gold-gradient mb-6 uppercase tracking-widest border-b theme-border pb-2 flex items-center gap-2">
                    <span class="translation-target">Jumlah Markah Terkumpul</span> <span class="text-[10px] bg-[#10b981]/20 text-[#10b981] px-2 py-0.5 rounded border border-[#10b981]/40 translation-target font-bold">KLIK GRAF</span>
                </h3>
                <div class="h-64 relative w-full overflow-x-auto overflow-y-hidden modal-scrollbar pb-2">
                    <div class="h-full min-w-[800px]">
                        <canvas id="totalChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card overflow-hidden mb-12">
            <div class="px-6 py-5 border-b theme-border flex justify-between items-center" style="background: rgba(0,0,0,0.1);">
                <h3 class="text-lg font-bold theme-text-gold uppercase tracking-widest translation-target">Papan Pendahulu (Leaderboard)</h3>
                <span class="text-xs text-green-500 font-bold flex items-center gap-1 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> <span class="translation-target">Live Data</span>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs uppercase tracking-widest font-bold border-b theme-border" style="background: var(--table-header); color: var(--text-gold-dark);">
                            <th class="px-6 py-4 translation-target">Ked.</th>
                            <th class="px-6 py-4 translation-target">Maklumat Staf</th>
                            <th class="px-6 py-4 translation-target">ID / Jabatan</th>
                            <th class="px-6 py-4 translation-target">Kemajuan Modul</th>
                            <th class="px-6 py-4 translation-target">Mata (Pts)</th>
                            <th class="px-6 py-4 text-center translation-target">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="leaderboard-body" class="divide-y theme-border text-sm font-medium">
                        @forelse($paginatedStaff as $index => $staff)
                        @php 
                            $actualRank = ($paginatedStaff->currentPage() - 1) * $paginatedStaff->perPage() + $loop->iteration; 
                        @endphp
                        <tr id="staff-row-{{ $staff->id }}" class="transition duration-300" style="cursor: default;" onmouseover="this.style.background='var(--table-row-hover)'" onmouseout="this.style.background='transparent'">
                            <td class="px-6 py-4">
                                @if($actualRank == 1)
                                    <span class="bg-gradient-to-r from-[#f0d56f] to-[#d4af37] text-[#0f2818] font-black px-3 py-1 rounded-full shadow-md">1</span>
                                @elseif($actualRank == 2)
                                    <span class="bg-gray-300 text-gray-800 font-bold px-3 py-1 rounded-full shadow-sm">2</span>
                                @elseif($actualRank == 3)
                                    <span class="bg-amber-600 text-white font-bold px-3 py-1 rounded-full shadow-sm">3</span>
                                @else
                                    <span class="font-bold theme-text-muted ml-2">{{ $actualRank }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold theme-text-main text-base flex items-center gap-2">
                                    {{ $staff->name }}
                                    <span id="status-badge-{{ $staff->id }}" class="px-2 py-0.5 text-[10px] bg-red-500 text-white rounded-full font-bold uppercase tracking-widest shadow-md translation-target {{ $staff->is_suspended ? '' : 'hidden' }}">Disekat</span>
                                </div>
                                <div class="theme-text-muted opacity-80 text-xs mt-1 font-semibold">{{ $staff->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold theme-text-gold tracking-wide translation-target">{{ $staff->staff_id ?? 'Tiada ID' }}</div>
                                <div class="text-xs theme-text-muted opacity-70 mt-1 uppercase font-semibold translation-target">{{ $staff->department ?? 'Jabatan Zakat' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php 
                                    $percentage = $totalRooms > 0 ? ($staff->rooms_completed / $totalRooms) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-24 h-2 rounded-full overflow-hidden border theme-border" style="background: rgba(0,0,0,0.2);">
                                        <div class="h-full bg-gradient-to-r from-[#10b981] to-[#34d399] rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold theme-text-muted">{{ $staff->rooms_completed ?? 0 }}/{{ $totalRooms }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-black text-xl theme-text-gold drop-shadow-md">{{ $staff->calculated_score }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openStaffModal({{ $index }})" class="theme-text-gold-dark hover:text-white theme-icon-bg hover:bg-[#10b981] p-2 rounded-lg transition shadow-sm" title="Lihat Butiran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943-9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>

                                    <button onclick="ajaxAction('{{ route('admin.users.toggle-suspend', $staff->id) }}', 'Adakah anda pasti mahu MENGUBAH status staf ini?', 'suspend', {{ $staff->id }})" id="suspend-btn-{{ $staff->id }}" class="{{ $staff->is_suspended ? 'text-green-500 bg-green-500/10 hover:bg-green-500' : 'text-orange-500 bg-orange-500/10 hover:bg-orange-500' }} hover:text-white p-2 rounded-lg transition shadow-sm" title="{{ $staff->is_suspended ? 'Aktifkan Akses' : 'Sekat Akses' }}">
                                        @if($staff->is_suspended)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        @endif
                                    </button>

                                    <button onclick="ajaxAction('{{ route('admin.users.destroy', $staff->id) }}', 'AMARAN KERAS:\nAdakah anda pasti mahu MEMADAM staf ini?\nSemua rekod dan markah akan HILANG SELAMANYA!', 'delete', {{ $staff->id }})" class="text-red-500 bg-red-500/10 hover:bg-red-500 hover:text-white p-2 rounded-lg transition shadow-sm" title="Padam Staf">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center theme-text-muted opacity-50 m-4 font-bold">
                                <div class="text-4xl mb-3">📭</div>
                                <span class="translation-target">Tiada data staf lagi. Minta staf mendaftar dan mula bermain!</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($paginatedStaff->hasPages())
            <div class="px-6 py-4 border-t theme-border flex flex-col sm:flex-row justify-between items-center gap-4" style="background: rgba(0,0,0,0.05);">
                <div class="text-sm theme-text-muted font-semibold">
                    <span class="translation-target">Memaparkan</span> <span class="font-bold theme-text-gold">{{ $paginatedStaff->firstItem() }}</span> <span class="translation-target">hingga</span> <span class="font-bold theme-text-gold">{{ $paginatedStaff->lastItem() }}</span> <span class="translation-target">daripada</span> <span class="font-bold theme-text-gold">{{ $paginatedStaff->total() }}</span> <span class="translation-target">staf</span>
                </div>
                <div class="flex gap-2">
                    @if ($paginatedStaff->onFirstPage())
                        <span class="px-4 py-2 rounded border theme-border opacity-50 cursor-not-allowed text-sm font-bold theme-text-muted translation-target">« Sebelumnya</span>
                    @else
                        <a href="{{ $paginatedStaff->previousPageUrl() }}" class="px-4 py-2 rounded border theme-border theme-text-gold hover:bg-[#10b981]/10 transition text-sm font-bold translation-target">« Sebelumnya</a>
                    @endif

                    @if ($paginatedStaff->hasMorePages())
                        <a href="{{ $paginatedStaff->nextPageUrl() }}" class="px-4 py-2 rounded border theme-border theme-text-gold hover:bg-[#10b981]/10 transition text-sm font-bold translation-target">Seterusnya »</a>
                    @else
                        <span class="px-4 py-2 rounded border theme-border opacity-50 cursor-not-allowed text-sm font-bold theme-text-muted translation-target">Seterusnya »</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </main>

    <div id="staffModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4" style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px);">
        <div class="bg-white text-gray-800 w-full max-w-xl p-6 relative border-2 border-[#10b981] rounded-2xl shadow-2xl transition-all duration-300">
            
            <div class="absolute top-4 right-4 z-50 flex items-center gap-2">
                <button type="button" onclick="toggleModalFullscreen(this)" class="text-gray-400 hover:text-[#10b981] bg-gray-100 hover:bg-emerald-50 p-1.5 rounded-lg transition-all duration-300" title="Skrin Penuh">
                    <svg class="w-5 h-5 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </button>
                <button type="button" onclick="closeStaffModal()" class="text-gray-400 hover:text-red-500 bg-gray-100 hover:bg-red-50 p-1.5 rounded-lg transition-all duration-300" title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex items-center gap-4 mb-6 border-b border-gray-200 pb-4 mt-2">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#10b981] to-[#047857] flex items-center justify-center text-white font-black text-2xl shadow-lg" id="modalInitials">A</div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-wide" id="modalName">Nama Staf</h2>
                    <p class="text-sm text-gray-500 font-semibold" id="modalEmail">emel@lznk.com</p>
                    <span class="inline-block mt-1 px-2 py-0.5 border border-amber-300 bg-amber-50 rounded text-xs text-amber-600 font-bold tracking-widest uppercase" id="modalStaffId">ID: LZNK001</span>
                </div>
            </div>

            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3 translation-target">Pecahan Markah Modul</h3>
            <div id="modalBreakdown" class="space-y-2 mb-6 max-h-60 overflow-y-auto pr-2 transition-all duration-300 modal-scrollbar"></div>

            <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 flex justify-between items-center mb-6 mt-auto">
                <span class="text-gray-800 font-bold uppercase tracking-widest translation-target">Markah Keseluruhan:</span>
                <span class="text-3xl font-black text-amber-500 drop-shadow-sm" id="modalTotalScore">0</span>
            </div>

            <div class="border-t border-red-200 pt-4 mt-4 flex justify-between items-center">
                <p class="text-xs text-red-500 font-bold w-2/3 translation-target">Memadam rekod akan mengosongkan semua markah staf ini.</p>
                <form id="resetForm" method="POST" action="" onsubmit="return confirm('AMARAN: Adakah anda pasti mahu RESET semua markah untuk staf ini?');">
                @csrf
                @method('PATCH') <button type="submit" class="bg-red-50 border border-red-200 hover:bg-red-500 text-red-600 hover:text-white text-sm font-bold py-2 px-4 rounded-lg transition translation-target">
                Reset Prestasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="chartModal" class="fixed inset-0 z-[105] hidden items-center justify-center p-4" style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px);">
        <div class="bg-white text-gray-800 w-full max-w-lg p-6 relative border-2 border-[#10b981] rounded-2xl shadow-2xl transition-all duration-300">
            <div class="absolute top-4 right-4 z-50">
                <button type="button" onclick="closeChartModal()" class="text-gray-400 hover:text-red-500 bg-gray-100 hover:bg-red-50 p-1.5 rounded-lg transition-all duration-300" title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="mb-5 border-b border-gray-200 pb-4 mt-2">
                <h2 class="text-xl font-black text-[#047857] tracking-wide" id="chartModalTitle">Tajuk Carta</h2>
                <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest translation-target" id="chartModalSubtitle">Senarai Penyumbang Data</p>
            </div>

            <div class="overflow-y-auto max-h-[60vh] pr-2 modal-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-widest font-bold border-b border-gray-200 text-gray-500">
                            <th class="py-2 px-3 translation-target">NAMA STAF</th>
                            <th class="py-2 px-3 translation-target">ID STAF</th>
                            <th class="py-2 px-3 text-right translation-target" id="th-mark">MARKAH</th>
                        </tr>
                    </thead>
                    <tbody id="chartModalBody" class="text-sm">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Latar Belakang Zarah
        const container = document.getElementById('particlesContainer');
        for (let i = 0; i < 40; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            const size = Math.random() * 4 + 2;
            particle.style.width = size + 'px'; particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 15 + 10) + 's';
            particle.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(particle);
        }

        // ==========================================
        // 🌟 LOGIK SUIS TEMA
        // ==========================================
        const themeToggle = document.getElementById('theme-toggle');
        const toggleCircle = document.getElementById('toggle-circle');
        const body = document.body;

        const moonIcon = '<svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>';
        const sunIcon = '<svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>';

        if (body.classList.contains('light-mode')) {
            themeToggle.checked = true;
            toggleCircle.innerHTML = sunIcon;
        } else {
            toggleCircle.innerHTML = moonIcon;
        }

        function updateChartsTheme(isLight) {
            const textColor = isLight ? '#022c22' : '#a8d5ba'; 
            const gridColor = isLight ? 'rgba(6, 78, 59, 0.15)' : 'rgba(249, 249, 249, 0.05)';
            
            Chart.defaults.color = textColor;
            
            const charts = [
                window.scoreChartInstance, 
                window.progressChartInstance, 
                window.averageChartInstance, 
                window.totalChartInstance
            ];

            charts.forEach(chart => {
                if (chart) {
                    if (chart.options.scales.x) {
                        if (chart.options.scales.x.grid) chart.options.scales.x.grid.color = gridColor;
                        if (chart.options.scales.x.ticks) chart.options.scales.x.ticks.color = textColor;
                    }
                    if (chart.options.scales.y) {
                        if (chart.options.scales.y.grid) chart.options.scales.y.grid.color = gridColor;
                        if (chart.options.scales.y.ticks) chart.options.scales.y.ticks.color = textColor;
                    }
                    chart.update();
                }
            });
        }

        themeToggle.addEventListener('change', () => {
            if (themeToggle.checked) {
                body.classList.add('light-mode'); localStorage.setItem('theme', 'light');
                toggleCircle.innerHTML = sunIcon; updateChartsTheme(true);
            } else {
                body.classList.remove('light-mode'); localStorage.setItem('theme', 'dark');
                toggleCircle.innerHTML = moonIcon; updateChartsTheme(false);
            }
        });

        // ==========================================
        // 🌐 KAMUS TERJEMAHAN MAGIS (Auto-Scanner)
        // ==========================================
        const enDictionary = {
            "Pusat Kawalan Admin": "Admin Control Center",
            "Pemantauan LZNK Cakna Siber": "LZNK Cyber Awareness Monitoring",
            "Urus Modul": "Manage Modules",
            "Eksport": "Export",
            "Keluar": "Logout",
            "Total Staf": "Total Staff",
            "Aktif": "Active",
            "Tertinggi": "Highest",
            "Rekod Terbaik": "Best Record",
            "Purata": "Average",
            "Tamat Misi": "Completed",
            "staf": "staff",
            "Taburan Markah Keseluruhan": "Overall Score Distribution",
            "Kadar Kemajuan Modul": "Module Progress Rate",
            "Purata Markah Staf": "Average Staff Score",
            "Jumlah Markah Terkumpul": "Total Accumulated Score",
            "Papan Pendahulu (Leaderboard)": "Leaderboard",
            "Live Data": "Live Data",
            "Ked.": "Rank",
            "Maklumat Staf": "Staff Info",
            "ID / Jabatan": "ID / Department",
            "Kemajuan Modul": "Module Progress",
            "Mata (Pts)": "Points (Pts)",
            "Tindakan": "Action",
            "KLIK GRAF": "CLICK GRAPH",
            "Disekat": "Suspended",
            "Pecahan Markah Modul": "Module Score Breakdown",
            "Markah Keseluruhan:": "Overall Score:",
            "Memadam rekod akan mengosongkan semua markah staf ini.": "Resetting records will clear all marks for this staff.",
            "Reset Prestasi": "Reset Performance",
            "Tiada data staf lagi. Minta staf mendaftar dan mula bermain!": "No staff data yet. Ask staff to register and start playing!",
            "Memaparkan": "Showing",
            "hingga": "to",
            "daripada": "of",
            "« Sebelumnya": "« Previous",
            "Seterusnya »": "Next »",
            "Tiada ID": "No ID",
            "Jabatan Zakat": "Zakat Dept",
            "KESELURUHAN": "OVERALL",
            "NAMA STAF": "STAFF NAME",
            "ID STAF": "STAFF ID",
            "MARKAH": "SCORE",
            "Senarai Penyumbang Data": "Data Contributor List"
        };

        const currentLang = localStorage.getItem('lang') || 'ms';

        function applyTranslation() {
            const btnEn = document.getElementById('lang-en');
            const btnMs = document.getElementById('lang-ms');

            if (currentLang === 'en') {
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm";
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                
                document.querySelectorAll('.translation-target').forEach(el => {
                    const text = el.textContent.trim();
                    if (text && enDictionary[text]) {
                        el.textContent = enDictionary[text];
                    }
                });
            } else {
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm";
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
            }
        }

        function switchLanguage(lang) {
            localStorage.setItem('lang', lang);
            window.location.reload(); 
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyTranslation();
            // 🔥 REMOVED THE INFINITE RELOAD TRAP HERE! 🔥
        });

        // ==========================================
        // 🚀 FUNGSI AJAX - TINDAKAN TANPA REFRESH
        // ==========================================
        function showNotification(message, type = 'success') {
            const notif = document.getElementById('ajax-notification');
            const msgEl = document.getElementById('ajax-message');
            const iconEl = document.getElementById('ajax-icon');
            
            msgEl.textContent = message;
            
            if(type === 'success') {
                notif.className = 'mb-6 px-4 py-3 rounded-lg flex items-center gap-3 font-semibold transition-opacity duration-500 bg-green-500/10 border border-green-500 text-green-500';
                iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            } else {
                notif.className = 'mb-6 px-4 py-3 rounded-lg flex items-center gap-3 font-semibold transition-opacity duration-500 bg-red-500/10 border border-red-500 text-red-500';
                iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            }
            
            notif.classList.remove('hidden');
            setTimeout(() => { notif.classList.add('opacity-100'); }, 10);
            
            setTimeout(() => {
                notif.classList.remove('opacity-100');
                setTimeout(() => { notif.classList.add('hidden'); }, 500);
            }, 3000);
        }

        async function ajaxAction(url, confirmMessage, actionType, staffId) {
            if (!confirm(confirmMessage)) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const actualMethod = actionType === 'delete' ? 'DELETE' : 'PATCH';
            
            try {
                const response = await fetch(url, {
                    method: actualMethod, 
                    redirect: 'error', 
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest', 
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const contentType = response.headers.get("content-type");
                
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const data = await response.json();

                    if (response.ok && data.success) {
                        
                        // 1. Tunjuk notifikasi hijau/merah
                        const btn = document.getElementById('suspend-btn-' + staffId);
                        if (actionType === 'suspend' && btn && btn.classList.contains('text-orange-500')) {
                            showNotification(data.message, 'error'); // Banner merah bila "Disekat"
                        } else {
                            showNotification(data.message, 'success'); // Banner hijau bila "Diaktifkan/Dipadam"
                        }

                        // 2. 🔥 AUTO-REFRESH dengan Scroll Memory!
                        setTimeout(() => {
                            sessionStorage.setItem('savedScrollPosition', window.scrollY);
                            window.location.reload();
                        }, 1200);

                    } else {
                        showNotification(data.error || 'Ralat pelayan.', 'error');
                    }
                } else {
                    showNotification('Tindakan berjaya, memuat semula sistem...', 'success');
                    setTimeout(() => {
                        sessionStorage.setItem('savedScrollPosition', window.scrollY);
                        window.location.reload();
                    }, 1200);
                }
            } catch (error) {
                if (error.name === 'TypeError' || error.message.includes('redirect')) {
                     showNotification('Tindakan selesai. Memuat semula...', 'success');
                     setTimeout(() => {
                         sessionStorage.setItem('savedScrollPosition', window.scrollY);
                         window.location.reload();
                     }, 1200);
                } else {
                     showNotification('Ralat komunikasi sistem.', 'error');
                }
            }
        }

        // ==========================================
        // 👨‍💼 LOGIK MODAL STAF (PUTIH BERSIH)
        // ==========================================
        const allStaffData = {!! json_encode($paginatedStaff->values()) !!};

        function openStaffModal(index) {
            const staff = allStaffData[index];
            document.getElementById('modalName').textContent = staff.name;
            document.getElementById('modalEmail').textContent = staff.email;
            document.getElementById('modalStaffId').textContent = 'ID: ' + (staff.staff_id ? staff.staff_id : (currentLang === 'en' ? 'NO ID' : 'TIADA'));
            document.getElementById('modalInitials').textContent = staff.name.charAt(0).toUpperCase();
            document.getElementById('modalTotalScore').textContent = staff.calculated_score;

            const breakdownContainer = document.getElementById('modalBreakdown');
            breakdownContainer.innerHTML = ''; 
            
            staff.breakdown.forEach(modul => {
                let statusHtml = ''; let historyHtml = ''; 
                if (modul.score !== null) {
                    statusHtml = `<span class="text-amber-500 font-bold text-lg">${modul.score} <span class="text-xs font-normal">pts</span></span>`;
                    if (modul.history && modul.history.length > 0) {
                        const histLabel = currentLang === 'en' ? 'Result Audit Trail:' : 'Jejak Audit Keputusan:';
                        historyHtml = `<div class="mt-4 text-xs border-t border-gray-200 pt-4 space-y-3"><p class="text-amber-600 font-bold uppercase tracking-widest text-[10px]">${histLabel}</p>`;
                        modul.history.forEach((h, i) => {
                            let pointColor = h.points > 0 ? 'text-emerald-600' : (h.points < 0 ? 'text-red-600' : 'text-gray-500');
                            const actionLabel = currentLang === 'en' ? 'Action:' : 'Tindakan:';
                            historyHtml += `<div class="p-3 rounded-lg border border-gray-200 bg-white shadow-sm"><p class="text-gray-800 font-bold mb-2 leading-relaxed"><span class="text-amber-600 font-bold">Q${i+1}:</span> ${h.question}</p><div class="flex justify-between items-center px-3 py-2 rounded-lg bg-gray-50"><p class="${pointColor} font-bold"><span>${actionLabel}</span> ${h.answer}</p><span class="${pointColor} font-bold px-2 py-0.5 rounded shadow-sm border border-current opacity-80">${h.points > 0 ? '+'+h.points : h.points}</span></div></div>`;
                        });
                        historyHtml += `</div>`;
                    }
                } else {
                    const noStartLabel = currentLang === 'en' ? 'Not Started' : 'Belum Mula';
                    statusHtml = `<span class="text-gray-400 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-widest border border-gray-200 bg-white">${noStartLabel}</span>`;
                }

                breakdownContainer.innerHTML += `<div class="p-4 rounded-xl border border-gray-200 bg-gray-50 mb-3 transition"><div class="flex justify-between items-center"><span class="text-gray-800 text-sm font-bold uppercase tracking-wider">${modul.room_title}</span>${statusHtml}</div>${historyHtml}</div>`;
            });

            document.getElementById('resetForm').action = `/admin/users/${staff.id}/reset`;
            const modal = document.getElementById('staffModal');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            modal.children[0].animate([{ opacity: 0, transform: 'scale(0.95) translateY(10px)' }, { opacity: 1, transform: 'scale(1) translateY(0)' }], { duration: 200, easing: 'ease-out' });
        }

        function toggleModalFullscreen(btn) {
            const modalCard = document.querySelector('#staffModal > div.bg-white');
            const breakdownScrollArea = document.getElementById('modalBreakdown');
            
            if (!modalCard) return;

            if (modalCard.classList.contains('max-w-full')) {
                modalCard.classList.remove('max-w-full', 'h-[95vh]');
                modalCard.classList.add('max-w-xl');
                
                breakdownScrollArea.classList.remove('max-h-[70vh]');
                breakdownScrollArea.classList.add('max-h-60');
                
                btn.innerHTML = `<svg class="w-5 h-5 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>`;
                btn.setAttribute('title', currentLang === 'en' ? 'Fullscreen' : 'Skrin Penuh');
            } else {
                modalCard.classList.remove('max-w-xl');
                modalCard.classList.add('max-w-full', 'h-[95vh]');
                
                breakdownScrollArea.classList.remove('max-h-60');
                breakdownScrollArea.classList.add('max-h-[70vh]');
                
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h6m0 0v6m0-6l-7 7m17-11h-6m0 0V4m0 6l-7-7m17 11h-6m0 0v6m0-6l7 7"></path></svg>`;
                btn.setAttribute('title', currentLang === 'en' ? 'Minimize' : 'Kecilkan Skrin');
            }
        }

        function closeStaffModal() {
            document.getElementById('staffModal').classList.add('hidden');
            document.getElementById('staffModal').classList.remove('flex');
            
            const modalCard = document.querySelector('#staffModal > div.bg-white');
            const breakdownScrollArea = document.getElementById('modalBreakdown');
            const expandBtn = modalCard ? modalCard.querySelector('button[title="Kecilkan Skrin"], button[title="Minimize"]') : null;

            if (modalCard && modalCard.classList.contains('max-w-full')) {
                modalCard.classList.remove('max-w-full', 'h-[95vh]');
                modalCard.classList.add('max-w-xl');
                
                if (breakdownScrollArea) {
                    breakdownScrollArea.classList.remove('max-h-[70vh]');
                    breakdownScrollArea.classList.add('max-h-60');
                }
                
                if(expandBtn) {
                    expandBtn.innerHTML = `<svg class="w-5 h-5 expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>`;
                    expandBtn.setAttribute('title', currentLang === 'en' ? 'Fullscreen' : 'Skrin Penuh');
                }
            }
        }
        document.getElementById('staffModal').addEventListener('click', function(e) { if (e.target === this) closeStaffModal(); });


        // ==========================================
        // 📊 CARTA & LOGIK MODAL KLIK CARTA (PUTIH BERSIH)
        // ==========================================
        const distDetails = {!! json_encode($distributionDetails ?? []) !!};
        const roomDetails = {!! json_encode($roomDetails ?? []) !!};
        const overallDetails = {!! json_encode($overallDetails ?? []) !!};

        function openChartModal(title, colMark, dataArray) {
            document.getElementById('chartModalTitle').textContent = title;
            document.getElementById('th-mark').textContent = colMark;
            
            const tbody = document.getElementById('chartModalBody');
            tbody.innerHTML = '';
            
            if (!dataArray || dataArray.length === 0) {
                const noDataText = currentLang === 'en' ? 'No score contributor records found.' : 'Tiada rekod penyumbang markah ditemui.';
                tbody.innerHTML = `<tr><td colspan="3" class="py-8 text-center text-gray-500 text-xs font-bold">${noDataText}</td></tr>`;
            } else {
                dataArray.forEach((row, index) => {
                    let markColor = row.score > 0 ? 'text-emerald-600' : (row.score < 0 ? 'text-red-600' : 'text-gray-400');
                    let hiddenClass = index >= 5 ? 'hidden extra-row' : '';
                    
                    tbody.innerHTML += `
                        <tr class="transition ${hiddenClass} hover:bg-emerald-50 border-b border-gray-100 last:border-0">
                            <td class="py-3 px-3 font-bold text-gray-800">${row.name}</td>
                            <td class="py-3 px-3 text-amber-600 font-bold text-xs tracking-wider">${row.staff_id}</td>
                            <td class="py-3 px-3 text-right font-black text-lg drop-shadow-sm ${markColor}">${row.score}</td>
                        </tr>
                    `;
                });

                if (dataArray.length > 5) {
                    const viewAllText = currentLang === 'en' ? 'View All' : 'Lihat Semua';
                    const staffText = currentLang === 'en' ? 'Staff' : 'Staf';
                    tbody.innerHTML += `
                        <tr id="expand-row">
                            <td colspan="3" class="py-4 text-center">
                                <button type="button" onclick="expandChartList()" class="inline-flex items-center gap-2 px-5 py-2 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-[#10b981] text-[#047857] bg-emerald-50 hover:bg-emerald-100 transition-all shadow-sm">
                                    <span>${viewAllText}</span> (${dataArray.length} <span>${staffText}</span>)
                                    <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </td>
                        </tr>
                    `;
                }
            }
            
            const modal = document.getElementById('chartModal');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            modal.children[0].animate([{ opacity: 0, transform: 'scale(0.95) translateY(10px)' }, { opacity: 1, transform: 'scale(1) translateY(0)' }], { duration: 200, easing: 'ease-out' });
        }

        function expandChartList() {
            const tbody = document.getElementById('chartModalBody');
            const hiddenRows = tbody.querySelectorAll('.extra-row');
            
            hiddenRows.forEach(row => {
                row.classList.remove('hidden');
                row.animate([
                    { opacity: 0, transform: 'translateY(-10px)' }, 
                    { opacity: 1, transform: 'translateY(0)' }
                ], { duration: 300, easing: 'ease-out' });
            });
            
            const expandRow = document.getElementById('expand-row');
            if (expandRow) expandRow.remove();
        }

        function closeChartModal() {
            document.getElementById('chartModal').classList.add('hidden'); document.getElementById('chartModal').classList.remove('flex');
        }
        document.getElementById('chartModal').addEventListener('click', function(e) { if (e.target === this) closeChartModal(); });

        // ==========================================
        // 📈 SETUP CHART.JS (KALIS PELURU!)
        // ==========================================
        const isLightInitial = document.body.classList.contains('light-mode');
        
        const initTextColor = isLightInitial ? '#022c22' : '#a8d5ba'; 
        const initGridColor = isLightInitial ? 'rgba(6, 78, 59, 0.15)' : 'rgba(212, 175, 55, 0.05)';
        
        Chart.defaults.color = initTextColor;
        Chart.defaults.font.family = "'Segoe UI', sans-serif";
        Chart.defaults.font.size = 13; 
        Chart.defaults.font.weight = 'bold'; 
        
        const gridConfigInit = { 
            color: initGridColor, 
            drawBorder: false 
        };

        const rawLabelsMs = {!! json_encode($roomLabels ?? []) !!};
        const roomTranslations = {!! json_encode(\App\Models\Room::pluck('title_en', 'title')->toArray()) !!};

        const rawLabelsDisplay = rawLabelsMs.map(label => {
            if (currentLang === 'en') {
                return roomTranslations[label] || label;
            }
            return label;
        });

        const multiLineLabels = rawLabelsDisplay.map(label => {
            const words = label.split(' '); let lines = []; let currentLine = words[0] || '';
            for (let i = 1; i < words.length; i++) {
                if (currentLine.length + words[i].length + 1 <= 25) { currentLine += ' ' + words[i]; } 
                else { lines.push(currentLine); currentLine = words[i]; }
            }
            lines.push(currentLine); return lines; 
        });

        const labelTotalStaff = currentLang === 'en' ? 'Total Staff' : 'Jumlah Staf';
        const labelCompletion = currentLang === 'en' ? 'Completion Rate (%)' : 'Kadar Siap (%)';
        const labelAvgScore = currentLang === 'en' ? 'Average Score' : 'Purata Markah';
        const labelTotalScore = currentLang === 'en' ? 'Total Score' : 'Jumlah Markah';
        const labelOverall = currentLang === 'en' ? 'OVERALL' : 'KESELURUHAN';

        const scoreCtx = document.getElementById('scoreChart').getContext('2d');
        let gradientGold = scoreCtx.createLinearGradient(0, 0, 0, 400);
        gradientGold.addColorStop(0, '#f0d56f'); gradientGold.addColorStop(1, '#8b6508');

        const dynamicBucketLabels = {!! json_encode($bucketLabels ?? ['0-20', '21-40', '41-60', '61-80', '81+']) !!};

        window.scoreChartInstance = new Chart(scoreCtx, {
            type: 'bar',
            data: {
                labels: dynamicBucketLabels,
                datasets: [{
                    label: labelTotalStaff,
                    data: {!! json_encode($distribution ?? [0,0,0,0,0]) !!},
                    backgroundColor: gradientGold,
                    borderRadius: 6, borderWidth: 1, borderColor: '#fef08a', barPercentage: 0.6
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                onHover: (event, chartElement) => { event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default'; },
                onClick: (e, elements) => {
                    if (!elements.length) return;
                    const idx = elements[0].index;
                    const labels = dynamicBucketLabels;
                    const titleText = currentLang === 'en' ? 'Overall Score: ' : 'Markah Keseluruhan: ';
                    const colText = currentLang === 'en' ? 'POINTS' : 'MATA (PTS)';
                    openChartModal(titleText + labels[idx], colText, distDetails[idx] || []);
                },
                scales: { 
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: initTextColor }, grid: gridConfigInit }, 
                    x: { grid: { display: false }, ticks: { color: initTextColor } } 
                }, 
                plugins: { legend: { display: false } } 
            }
        });

        const progressCtx = document.getElementById('progressChart').getContext('2d');
        window.progressChartInstance = new Chart(progressCtx, {
            type: 'bar',
            data: {
                labels: multiLineLabels,
                datasets: [{
                    label: labelCompletion,
                    data: {!! json_encode($roomProgress ?? []) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderRadius: 6, borderWidth: 1, borderColor: '#10b981', barPercentage: 0.7
                }]
            },
            options: { 
                indexAxis: 'y', responsive: true, maintainAspectRatio: false, 
                onHover: (event, chartElement) => { event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default'; },
                onClick: (e, elements) => {
                    if (!elements.length) return;
                    const idx = elements[0].index;
                    const titleMs = rawLabelsMs[idx];
                    const titleDisplay = rawLabelsDisplay[idx]; 
                    
                    const titleText = currentLang === 'en' ? 'Module Contributors: ' : 'Penyumbang Modul: ';
                    const colText = currentLang === 'en' ? 'MODULE POINTS' : 'MATA MODUL INI';
                    openChartModal(titleText + titleDisplay, colText, roomDetails[titleMs] || []);
                },
                scales: { 
                    x: { beginAtZero: true, max: 100, grid: gridConfigInit, ticks: { color: initTextColor } }, 
                    y: { grid: { display: false }, ticks: { autoSkip: false, color: initTextColor, font: { size: 13, weight: 'bold', lineHeight: 1.3 } } } 
                }, 
                plugins: { legend: { display: false } } 
            }
        });

        const averageCtx = document.getElementById('averageChart').getContext('2d');
        const avgLabels = [...multiLineLabels, [labelOverall]];
        const avgData = [...{!! json_encode($roomAverages ?? []) !!}, {{ $averageScore ?? 0 }}];
        const avgBgColors = avgData.map((_, i) => i === avgData.length - 1 ? gradientGold : 'rgba(56, 189, 248, 0.6)');
        const avgBorderColors = avgData.map((_, i) => i === avgData.length - 1 ? '#fef08a' : '#38bdf8');

        window.averageChartInstance = new Chart(averageCtx, {
            type: 'bar',
            data: {
                labels: avgLabels,
                datasets: [{
                    label: labelAvgScore,
                    data: avgData,
                    backgroundColor: avgBgColors,
                    borderRadius: 6, borderWidth: 1, borderColor: avgBorderColors, barPercentage: 0.6
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                onHover: (event, chartElement) => { event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default'; },
                onClick: (e, elements) => {
                    if (!elements.length) return;
                    const idx = elements[0].index;
                    let data, titleNameDisplay, colMark;
                    
                    if (idx === rawLabelsMs.length) {
                        titleNameDisplay = currentLang === 'en' ? 'Overall Staff Average' : 'Purata Keseluruhan Staf'; 
                        data = overallDetails; 
                        colMark = currentLang === 'en' ? 'OVERALL POINTS' : 'MATA KESELURUHAN';
                    } else {
                        const titleMs = rawLabelsMs[idx];
                        titleNameDisplay = rawLabelsDisplay[idx]; 
                        data = roomDetails[titleMs] || []; 
                        colMark = currentLang === 'en' ? 'MODULE POINTS' : 'MATA MODUL INI';
                    }
                    const titlePrefix = currentLang === 'en' ? 'Average: ' : 'Purata: ';
                    openChartModal(titlePrefix + titleNameDisplay, colMark, data);
                },
                scales: { 
                    y: { beginAtZero: true, grid: gridConfigInit, ticks: { color: initTextColor } }, 
                    x: { grid: { display: false }, ticks: { color: initTextColor } } 
                }, 
                plugins: { legend: { display: false } } 
            }
        });

        const totalCtx = document.getElementById('totalChart').getContext('2d');
        window.totalChartInstance = new Chart(totalCtx, {
            type: 'bar',
            data: {
                labels: multiLineLabels,
                datasets: [{
                    label: labelTotalScore,
                    data: {!! json_encode($roomTotals ?? []) !!},
                    backgroundColor: 'rgba(168, 85, 247, 0.6)',
                    borderRadius: 6, borderWidth: 1, borderColor: '#a855f7', barPercentage: 0.6
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                onHover: (event, chartElement) => { event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default'; },
                onClick: (e, elements) => {
                    if (!elements.length) return;
                    const idx = elements[0].index;
                    const titleMs = rawLabelsMs[idx];
                    const titleDisplay = rawLabelsDisplay[idx];
                    
                    const titleText = currentLang === 'en' ? 'Total Accumulated: ' : 'Jumlah Terkumpul: ';
                    const colText = currentLang === 'en' ? 'MODULE POINTS' : 'MATA MODUL INI';
                    openChartModal(titleText + titleDisplay, colText, roomDetails[titleMs] || []);
                },
                scales: { 
                    y: { beginAtZero: true, grid: gridConfigInit, ticks: { color: initTextColor } }, 
                    x: { grid: { display: false }, ticks: { color: initTextColor } } 
                }, 
                plugins: { legend: { display: false } } 
            }
        });

        // ==========================================
        // 🔥 TELEPORT SCROLL (KAEDAH KALIS PELURU)
        // ==========================================
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        // Gunakan 'window.onload' supaya ia tunggu SEMUA carta & jadual siap dilukis (ada ketinggian fizikal)
        window.addEventListener("load", function() {
            let savedScroll = sessionStorage.getItem('savedScrollPosition');
            
            if (savedScroll) {
                // Tukar teks (String) kepada Nombor (Integer) yang sah
                let exactPosition = parseInt(savedScroll, 10);
                
                // Paksa browser ke posisi tersebut 4 kali berturut-turut untuk mengalahkan 'auto-scroll' browser
                window.scrollTo(0, exactPosition);
                setTimeout(() => window.scrollTo(0, exactPosition), 10);
                setTimeout(() => window.scrollTo(0, exactPosition), 50);
                setTimeout(() => window.scrollTo(0, exactPosition), 100);
                
                // Padam memori selepas berjaya
                sessionStorage.removeItem('savedScrollPosition'); 
            }
        });
    </script>
    @include('partials.cursor')
</body>
</html>