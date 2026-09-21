<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Director Dashboard</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Share Tech Mono', monospace; overflow-x: hidden; }
        
        /* The glow effect for Dark Mode */
        .title-font { font-family: 'Poppins', sans-serif; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        
        /* Removes the text smudge effect in Light Mode */
        .light-mode .title-font { text-shadow: none; }

        :root {
            --bg-color: #000000; --text-color: #d1d5db; 
            
            /* DARK MODE VIDEO SETTINGS */
            --vid-opacity: 0.6;
            --video-filter: grayscale(100%) brightness(250%) contrast(200%);
            --vid-overlay: rgba(0, 0, 0, 0.6); 
            --blend-opacity: 0.3; /* Shows the purple tint */
            
            --card-bg: rgba(15, 10, 20, 0.85); --card-hover: rgba(25, 15, 30, 0.9);
            --title-color: #ffffff; --value-color: #ffffff; --badge-text: #000000;
            --btn-bg: rgba(255, 255, 255, 0.9); --glossy-sheen: none;
        }

        .light-mode {
            --bg-color: #e5e7eb; --text-color: #0f172a; 
            
            /* LIGHT MODE VIDEO SETTINGS - TRULY INVERTED */
            --vid-opacity: 0.8;
            --video-filter: grayscale(100%) invert(100%) contrast(250%) brightness(110%);
            
            /* Make the overlay transparent so the inverted video shows through! */
            --vid-overlay: rgba(255, 255, 255, 0.6);
            --blend-opacity: 0; /* Hides the purple tint completely */
            
            /* Make cards slightly transparent to show the wallpaper */
            --card-bg: rgba(255, 255, 255, 0.85); 
            --card-hover: rgba(255, 255, 255, 0.95);
            --title-color: #1e1b4b; 
            --value-color: #0f172a; 
            --badge-text: #ffffff;
            --btn-bg: rgba(15, 23, 42, 0.9);
            --glossy-sheen: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
        }

        /* --- Forced high-contrast text in Light Mode --- */
        .light-mode .text-gray-300, 
        .light-mode .text-gray-400, 
        .light-mode .text-gray-500,
        .light-mode .text-blue-400,
        .light-mode .text-indigo-300 { 
            color: #000000 !important; 
        }

        /* Fixed readability for text on white background in light mode */
        .light-mode .text-emerald-400 { color: #059669 !important; } /* Deep Green */
        .light-mode .text-emerald-500 { color: #047857 !important; }
        
        /* Make Fragment Boxes Clear in Light Mode */
        .fragment-box { background-color: rgba(0, 0, 0, 0.4); }
        .light-mode .fragment-box { 
            background-color: transparent !important; 
            box-shadow: none !important;
        }

        /* Enhance CLEARED Badge and Table Label readability */
        .light-mode .bg-emerald-900\/50 { background-color: #d1fae5 !important; }
        .light-mode .border-emerald-500\/30 { border-color: #10b981 !important; }

        /* Fix Control Buttons Contrast in Light Mode (Yellow, Orange, Red, Indigo) */
        .light-mode .text-yellow-400 { color: #d97706 !important; }
        .light-mode .bg-yellow-900\/30 { background-color: #fef3c7 !important; }
        .light-mode .border-yellow-500\/50 { border-color: #f59e0b !important; }
        
        .light-mode .text-orange-400 { color: #c2410c !important; }
        .light-mode .bg-orange-900\/30 { background-color: #ffedd5 !important; }
        .light-mode .border-orange-500\/50 { border-color: #f97316 !important; }
        
        .light-mode .text-red-400 { color: #b91c1c !important; }
        .light-mode .bg-red-900\/30 { background-color: #fee2e2 !important; }
        .light-mode .border-red-500\/50 { border-color: #ef4444 !important; }

        .light-mode .bg-indigo-900\/50 { background-color: #e0e7ff !important; }
        .light-mode .border-indigo-600 { border-color: #6366f1 !important; }
        
        .light-mode th { border-bottom-color: #cbd5e1; color: #ffffff !important; }
        .light-mode td { border-color: #e2e8f0; }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        
        /* Updated Dynamic Video Classes */
        .theme-video { opacity: var(--vid-opacity); transition: all 0.5s ease; filter: var(--video-filter); }
        .theme-blend { opacity: var(--blend-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; box-shadow: var(--glossy-sheen); backdrop-filter: blur(8px); }
        .theme-card:hover { background: var(--card-hover) !important; }
        .theme-title { color: var(--title-color) !important; }
        .theme-value { color: var(--value-color) !important; }

        .sc-anim { opacity: 0; transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); pointer-events: none; }
        .sc-in-left { transform: translateX(-100vw); }
        .sc-in-right { transform: translateX(100vw); }
        .sc-in-up { transform: translateY(100vh); }
        
        .d-1 { transition-delay: 0.1s; } .d-2 { transition-delay: 0.2s; } .d-3 { transition-delay: 0.3s; } .d-4 { transition-delay: 0.4s; } .d-5 { transition-delay: 0.5s; }

        body.loaded .sc-anim { opacity: 1; transform: translate(0, 0) !important; pointer-events: auto; }
        body.exiting .sc-anim { transition: all 0.5s cubic-bezier(0.7, 0, 0.84, 0); transition-delay: 0s !important; opacity: 0; pointer-events: none; }

        .lz-input { background-color: #100020; border: 1px solid #7e22ce; color: #e9d5ff; width: 100%; padding: 8px 12px; border-radius: 4px; font-family: 'Share Tech Mono', monospace; font-size: 0.875rem; text-align: center; font-weight: bold; letter-spacing: 0.2em; }
        .light-mode .lz-input { background-color: #f3e8ff; border-color: #a855f7; color: #4c1d95; }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(59,130,246,0.5); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(59,130,246,0.8); }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-10">

    @php
        // --- 🚨 ACCURATE COMPLETED STAFF COUNT (STRICTLY CORE MODULES) ---
        $completedStaff = \App\Models\GameProgress::where('level_1_completed', true)
            ->where('level_2_completed', true)
            ->where('level_3_completed', true)
            ->where('level_4_completed', true)
            ->where('level_5_completed', true)
            ->has('user')
            ->count();
    @endphp

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="theme-blend absolute inset-0 bg-purple-900 mix-blend-color pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-3">
        <button id="lang-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-8 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-purple-500/30 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <img src="{{ asset('img/logo2.png') }}" alt="LZNK Logo" class="h-12 md:h-16 w-auto object-contain">
                
                <div>
                    <h1 class="theme-title title-font font-bold text-3xl tracking-wider uppercase mb-1 text-purple-400" data-en="DIRECTOR OVERSIGHT" data-ms="PEMANTAUAN PENGARAH">DIRECTOR OVERSIGHT</h1>
                    <p class="text-purple-500 font-bold tracking-widest text-xs uppercase" data-en="ROOT MODE ACTIVE" data-ms="MOD ROOT AKTIF">ROOT MODE ACTIVE</p>
                </div>
            </div>
            <a href="{{ route('agent.mission') }}" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-emerald-500/50 text-emerald-400 hover:bg-emerald-500 hover:text-black transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Return to Agent View" data-ms="Kembali ke Paparan Ejen">Return to Agent View</span>
            </a>
        </header>
        @if(session('success') || session('error'))
            <div class="mb-6 flex justify-center w-full sc-anim sc-in-up d-1">
                @if(session('success'))
                    <div class="flex items-center gap-3 text-sm md:text-base text-emerald-400 bg-emerald-900/40 border border-emerald-500/50 px-6 py-3 rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.3)] animate-pulse backdrop-blur-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold tracking-widest uppercase">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="flex items-center gap-3 text-sm md:text-base text-red-400 bg-red-900/40 border border-red-500/50 px-6 py-3 rounded-lg shadow-[0_0_15px_rgba(239,68,68,0.3)] animate-pulse backdrop-blur-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold tracking-widest uppercase">{{ session('error') }}</span>
                    </div>
                @endif
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 sc-anim sc-in-left d-2">
            <div class="theme-card rounded-lg p-5 border border-purple-500/40 shadow-[0_0_15px_rgba(168,85,247,0.15)] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5 text-7xl">👥</div>
                <p class="text-lg md:text-2xl text-purple-500 font-black tracking-widest uppercase mb-2" data-en="TOTAL REGISTERED STAF" data-ms="JUMLAH STAF BERDAFTAR">TOTAL REGISTERED AGENTS</p>
                <p class="text-6xl font-extrabold theme-title drop-shadow-sm">{{ $totalStaff ?? 0 }}</p>
            </div>

            <div class="theme-card rounded-lg p-5 border border-blue-500/40 shadow-[0_0_15px_rgba(59,130,246,0.15)] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5 text-7xl">⚡</div>
                <p class="text-lg md:text-2xl text-blue-500 font-black tracking-widest uppercase mb-2" data-en="TOTAL STAFF IN ANSWERING PROGRESS" data-ms="JUMLAH STAF DALAM PROSES MENJAWAB">ACTIVE MISSION SESSIONS</p>
                @php
                    $activeSessions = 0;
                    if(isset($overallDetails)) {
                        $totalStarted = collect($overallDetails)->filter(function($agent) {
                            $score = data_get($agent, 'score', data_get($agent, 'calculated_score', 0));
                            return $score > 0;
                        })->count();
                        
                        $completed = $completedStaff ?? 0;
                        $activeSessions = max(0, $totalStarted - $completed);
                    }
                @endphp
                <p class="text-6xl font-extrabold theme-title drop-shadow-sm">{{ $activeSessions }}</p>
            </div>

            <div class="theme-card rounded-lg p-5 border border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.15)] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5 text-7xl">🛡️</div>
                <p class="text-lg md:text-2xl text-emerald-500 font-black tracking-widest uppercase mb-2" data-en="TOTAL SUCCEEDED STAFF IN COMPLETION" data-ms="JUMLAH STAF BERJAYA MENAMATKAN MISI">TOTAL SUCCEEDED STAFF IN COMPLETION</p>
                <p class="text-6xl font-extrabold theme-title drop-shadow-sm">{{ $completedStaff ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 sc-anim sc-in-left d-3">
                <div class="theme-card rounded-lg p-6 border border-purple-500/30 h-full flex flex-col">
                    <div class="flex items-center text-purple-400 text-sm mb-6 border-b border-purple-500/20 pb-3 font-bold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        <span data-en="System Directives" data-ms="Arahan Sistem">System Directives</span>
                    </div>

                    <div class="mb-6">
                        <label class="text-[9px] text-purple-400 font-bold uppercase tracking-widest mb-2 block" data-en="LEVEL 5 MASTER PASSWORD" data-ms="KATA LALUAN UTAMA TAHAP 5">LEVEL 5 MASTER PASSWORD</label>
                        <input type="text" class="lz-input" value="RECHECK" readonly>
                    </div>

                    <div class="mb-6 flex-grow">
                        <label class="text-[9px] text-purple-400 font-bold uppercase tracking-widest mb-2 block" data-en="FRAGMENT DISTRIBUTION" data-ms="PENGEDARAN SERPIHAN">FRAGMENT DISTRIBUTION</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="fragment-box border border-purple-500/20 p-2 rounded text-center shadow-inner flex flex-col justify-center">
                                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Lvl 1 Drop</span>
                                <span class="text-lg font-bold text-emerald-400 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]">R</span>
                            </div>
                            <div class="fragment-box border border-purple-500/20 p-2 rounded text-center shadow-inner flex flex-col justify-center">
                                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Lvl 2 Drop</span>
                                <span class="text-lg font-bold text-emerald-400 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]">E</span>
                            </div>
                            <div class="fragment-box border border-purple-500/20 p-2 rounded text-center shadow-inner flex flex-col justify-center">
                                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Lvl 3 Drop</span>
                                <span class="text-lg font-bold text-emerald-400 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]">C</span>
                            </div>
                            <div class="fragment-box border border-purple-500/20 p-2 rounded text-center shadow-inner flex flex-col justify-center">
                                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Lvl 4 Drop</span>
                                <span class="text-lg font-bold text-emerald-400 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]">H</span>
                            </div>
                            <div class="col-span-2 fragment-box border border-purple-500/20 p-3 rounded text-center shadow-inner flex flex-col justify-center">
                                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest block mb-1">Lvl 5 Verification</span>
                                <span class="text-xl font-bold text-emerald-400 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)] tracking-widest">ECK</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto space-y-3">
                        <a href="{{ route('admin.builder.modules') }}" class="w-full bg-emerald-900/30 hover:bg-emerald-500 text-yellow-400 hover:text-emerald-900 border border-emerald-500/50 hover:border-yellow-400 font-bold py-3 px-4 rounded text-xs uppercase tracking-widest text-center flex items-center justify-center gap-2 transition-colors nav-trigger shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 00-1-1H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1v-1z" /></svg>
                            <span data-en="Branching Editor" data-ms="Penyunting Cabang">Branching Editor</span>
                        </a>

                        <a href="{{ route('admin.levels') }}" class="w-full bg-transparent hover:bg-purple-900/40 text-purple-400 border border-purple-500/50 font-bold py-3 px-4 rounded text-xs uppercase tracking-widest text-center block transition-colors nav-trigger shadow-[0_0_15px_rgba(126,34,206,0.2)] hover:shadow-[0_0_15px_rgba(126,34,206,0.6)]">
                            <span data-en="Edit Mission Levels" data-ms="Sunting Tahap Misi">Edit Mission Levels</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 sc-anim sc-in-right d-3">
                <div class="theme-card rounded-lg p-6 border border-purple-500/30 h-full max-h-[600px] flex flex-col">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 border-b border-purple-500/20 pb-3 shrink-0 gap-4">
                        <div class="flex items-center text-purple-400 text-base md:text-xl font-bold tracking-widest uppercase">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span data-en="Agent Surveillance" data-ms="Pemantauan Ejen">Agent Surveillance</span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-2 bg-indigo-900/50 hover:bg-indigo-800 text-indigo-300 border border-indigo-600 px-4 py-1.5 rounded text-xs md:text-sm uppercase tracking-widest font-bold transition-colors shadow-[0_0_10px_rgba(99,102,241,0.3)] whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span data-en="Intel Gallery" data-ms="Galeri Intel">Intel Gallery</span>
                            </a>
                            
                            <a href="{{ route('admin.analytics', 1) }}" class="bg-blue-900/50 hover:bg-blue-800 text-blue-300 border border-blue-600 px-4 py-1.5 rounded text-xs md:text-sm uppercase tracking-widest font-bold transition-colors shadow-[0_0_10px_rgba(59,130,246,0.3)] whitespace-nowrap">
                                <span data-en="Room Analytics" data-ms="Analisis Bilik">Room Analytics</span>
                            </a>
                            
                            <a href="{{ route('admin.export') }}" class="bg-purple-900/50 hover:bg-purple-800 text-purple-300 border border-purple-600 px-4 py-1.5 rounded text-xs md:text-sm uppercase tracking-widest font-bold transition-colors shadow-[0_0_10px_rgba(168,85,247,0.3)] inline-block whitespace-nowrap">
                                <span data-en="Export Logs" data-ms="Eksport Log">Export Logs</span>
                            </a>
                        </div>
                        
                        
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 pr-2">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead class="uppercase tracking-widest text-xs text-purple-400 border-b border-purple-500/30 sticky top-0 bg-black/90 backdrop-blur z-10">
                                <tr>
                                    <th class="px-4 py-4" data-en="Agent" data-ms="Ejen">Agent</th>
                                    <th class="px-4 py-4 text-center" data-en="Level" data-ms="Tahap">Level</th>
                                    <th class="px-4 py-4 text-center" data-en="Score" data-ms="Markah">Score</th>
                                    <th class="px-4 py-4 text-center" data-en="Status" data-ms="Status">Status</th>
                                    <th class="px-4 py-4 text-center" data-en="Controls" data-ms="Kawalan">Controls</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono text-base opacity-90 divide-y divide-purple-500/10">
                                
                                @if(isset($paginatedStaff))
                                    @forelse($paginatedStaff as $staff)
                                        @php
                                            // 🔥 Check ONLY Core Modules for Status Tracking
                                            $coreCompleted = 0;
                                            for($i = 0; $i < 5; $i++) {
                                                if(isset($staff->breakdown[$i]) && $staff->breakdown[$i]['score'] !== null) {
                                                    $coreCompleted++;
                                                }
                                            }

                                            $lvlBadge = 'MOD 01'; $badgeColor = 'bg-emerald-800 text-white';
                                            if($coreCompleted == 1) { $lvlBadge = 'MOD 02'; $badgeColor = 'bg-gray-700 text-gray-200'; }
                                            elseif($coreCompleted == 2) { $lvlBadge = 'MOD 03'; $badgeColor = 'bg-blue-900/50 text-blue-400 border border-blue-500/30'; }
                                            elseif($coreCompleted == 3) { $lvlBadge = 'MOD 04'; $badgeColor = 'bg-purple-900/50 text-purple-400 border border-purple-500/30'; }
                                            elseif($coreCompleted == 4) { $lvlBadge = 'BOSS'; $badgeColor = 'bg-red-900/50 text-red-400 border border-red-500/30'; }
                                            elseif($coreCompleted == 5) { $lvlBadge = 'CLEARED'; $badgeColor = 'bg-emerald-900/50 text-emerald-400 border border-emerald-500/30'; }
                                        @endphp
                                        <tr class="hover:bg-purple-900/20 transition-colors {{ $staff->is_suspended ? 'opacity-50' : '' }}">
                                            <td class="px-4 py-4 font-bold text-emerald-400 text-lg">
                                                {{ $staff->name }}
                                                @if($staff->is_suspended)
                                                    <span class="ml-2 text-[10px] bg-red-900/80 text-red-300 px-2 py-1 rounded border border-red-500/50 uppercase tracking-widest align-middle">Suspended</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span class="px-3 py-1.5 rounded text-xs uppercase tracking-widest {{ $badgeColor }}">{{ $lvlBadge }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-center font-bold text-gray-300 text-lg">{{ $staff->calculated_score }}</td>
                                            <td class="px-4 py-4 text-center">
                                                @if($coreCompleted == 5)
                                                    <span class="text-emerald-500 font-bold text-xs uppercase tracking-widest">Completed</span>
                                                @elseif($staff->calculated_score > 0)
                                                    <span class="text-blue-500 font-bold text-xs uppercase tracking-widest">In Progress</span>
                                                @else
                                                    <span class="text-yellow-500 font-bold text-xs uppercase tracking-widest">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex justify-center gap-3 items-center">
                                                    @if($staff->id !== 'N/A')
                                                        <button type="button" onclick="inspectAgent('{{ $staff->name }}', '{{ $staff->email }}', {{ json_encode($staff->breakdown) }}, {{ $staff->calculated_score }})" class="p-2 bg-blue-900/30 text-blue-400 hover:bg-blue-500 hover:text-white rounded border border-blue-500/50 transition-colors" title="Inspect Agent">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                        </button>
                                                        
                                                        <form action="{{ route('admin.users.reset', $staff->id) }}" method="POST" onsubmit="return confirm('Wipe all mission data for {{ $staff->name }}? This resets their score to 0.');" class="m-0">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="p-2 bg-yellow-900/30 text-yellow-400 hover:bg-yellow-500 hover:text-black rounded border border-yellow-500/50 transition-colors" title="Reset Progress">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('admin.users.toggle-suspend', $staff->id) }}" method="POST" onsubmit="return confirm('{{ $staff->is_suspended ? 'Reactivate' : 'Suspend' }} agent {{ $staff->name }}?');" class="m-0">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="p-2 bg-orange-900/30 text-orange-400 hover:bg-orange-500 hover:text-white rounded border border-orange-500/50 transition-colors" title="{{ $staff->is_suspended ? 'Unsuspend Agent' : 'Suspend Agent' }}">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('admin.users.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('CRITICAL WARNING: Permanently delete agent {{ $staff->name }}? This cannot be undone.');" class="m-0">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="p-2 bg-red-900/30 text-red-400 hover:bg-red-500 hover:text-white rounded border border-red-500/50 transition-colors" title="Terminate Agent">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                            </button>
                                                        </form>

                                                    @else
                                                        <span class="text-[10px] text-gray-500 italic uppercase tracking-widest font-bold" data-en="Unreachable" data-ms="Tidak Dapat Dihubungi">Unreachable</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 italic font-mono text-xs uppercase tracking-widest" data-en="No agent data detected in mainframe." data-ms="Tiada data ejen dikesan dalam komputer utama.">No agent data detected in mainframe.</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-red-500 italic font-mono text-xs uppercase tracking-widest">Error: Dashboard Controller Not Connected.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if (isset($paginatedStaff) && method_exists($paginatedStaff, 'hasPages') && $paginatedStaff->hasPages())
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-purple-500/30 shrink-0">
                            <div>
                                @if ($paginatedStaff->onFirstPage())
                                    <span class="px-3 py-1 bg-gray-800/50 text-gray-500 rounded border border-gray-700/50 text-[10px] md:text-xs font-bold tracking-widest uppercase cursor-not-allowed" data-en="PREV" data-ms="KEMBALI">PREV</span>
                                @else
                                    <a href="{{ $paginatedStaff->previousPageUrl() }}" class="px-3 py-1 bg-purple-900/30 text-purple-400 hover:bg-purple-500 hover:text-white rounded border border-purple-500/50 transition-colors text-[10px] md:text-xs font-bold tracking-widest uppercase inline-block" data-en="PREV" data-ms="KEMBALI">PREV</a>
                                @endif
                            </div>
                            <div class="text-[10px] md:text-xs text-purple-400/70 font-mono tracking-widest uppercase" data-en="Page {{ $paginatedStaff->currentPage() }} of {{ $paginatedStaff->lastPage() }}" data-ms="Muka {{ $paginatedStaff->currentPage() }} dari {{ $paginatedStaff->lastPage() }}">
                                Page {{ $paginatedStaff->currentPage() }} of {{ $paginatedStaff->lastPage() }}
                            </div>
                            <div>
                                @if ($paginatedStaff->hasMorePages())
                                    <a href="{{ $paginatedStaff->nextPageUrl() }}" class="px-3 py-1 bg-purple-900/30 text-purple-400 hover:bg-purple-500 hover:text-white rounded border border-purple-500/50 transition-colors text-[10px] md:text-xs font-bold tracking-widest uppercase inline-block" data-en="NEXT" data-ms="SETERUSNYA">NEXT</a>
                                @else
                                    <span class="px-3 py-1 bg-gray-800/50 text-gray-500 rounded border border-gray-700/50 text-[10px] md:text-xs font-bold tracking-widest uppercase cursor-not-allowed" data-en="NEXT" data-ms="SETERUSNYA">NEXT</span>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <div id="inspect-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-8 transition-opacity duration-300 opacity-0" style="background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px);">
        
        <div class="theme-card w-full h-full max-h-[90vh] max-w-5xl p-6 md:p-10 flex flex-col relative border-2 border-blue-500/50 shadow-[0_0_40px_rgba(59,130,246,0.3)] rounded-xl transform scale-95 transition-transform duration-300" id="inspect-modal-content">
            
            <div class="absolute top-4 right-4 md:top-6 md:right-6">
                <button onclick="closeInspectModal()" class="text-gray-400 hover:text-red-500 transition-colors bg-black/50 hover:bg-black/80 rounded-full p-2" title="Close">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-4 md:gap-6 mb-6 md:mb-8 border-b border-blue-500/30 pb-6 shrink-0 text-center md:text-left">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-blue-900/50 border-2 border-blue-500 flex items-center justify-center text-blue-400 text-3xl md:text-4xl font-bold shadow-[0_0_20px_rgba(59,130,246,0.4)]" id="inspect-initial">A</div>
                <div class="mt-2 md:mt-0 flex flex-col justify-center h-full">
                    <h3 class="text-2xl md:text-4xl font-black theme-title uppercase tracking-widest mb-1 md:mb-2" id="inspect-name">Agent Name</h3>
                    <p class="text-sm md:text-base text-blue-400 font-mono tracking-widest" id="inspect-email">agent@shield.gov</p>
                </div>
            </div>

            <p class="text-xs md:text-sm text-gray-400 uppercase tracking-widest mb-3 font-bold shrink-0" data-en="Mission Breakdown" data-ms="Pecahan Misi">Mission Breakdown</p>
            
            <div id="inspect-breakdown" class="flex-grow space-y-3 mb-6 overflow-y-auto pr-4 font-mono text-sm md:text-base">
                </div>

            <div class="p-6 md:p-8 rounded-lg bg-black/60 border-2 border-blue-500/30 flex justify-between items-center shrink-0 shadow-inner">
                <span class="text-blue-400 font-black uppercase tracking-widest text-sm md:text-lg" data-en="Total Secured Score" data-ms="Jumlah Markah">Total Secured Score</span>
                <span class="text-4xl md:text-6xl font-black text-emerald-400 drop-shadow-[0_0_15px_rgba(16,185,129,0.8)]" id="inspect-total">0</span>
            </div>
        </div>
    </div>

    <script>
        // --- INITIALIZATION ---
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        window.addEventListener('DOMContentLoaded', () => { setTimeout(() => { document.body.classList.add('loaded'); }, 50); });

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') { bodyEl.classList.add('light-mode'); if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            translatables.forEach(el => {
                if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'H3' || el.tagName === 'DIV' || el.tagName === 'BUTTON' || el.tagName === 'TH' || el.tagName === 'LABEL' || el.tagName === 'A') {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan && !el.hasAttribute('data-en')) textSpan.innerText = el.getAttribute(`data-${lang}`);
                        else el.innerText = el.getAttribute(`data-${lang}`);
                    }
                }
            });
        }
        applyLanguage(currentLang);

        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                if(currentTheme === 'light') { bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE'; } 
                else { bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE'; }
            });
        }

        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        document.querySelectorAll('.nav-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault(); 
                const targetUrl = this.getAttribute('href');
                document.body.classList.remove('loaded');
                document.body.classList.add('exiting');
                setTimeout(() => { window.location.href = targetUrl; }, 500); 
            });
        });

        // --- 🚨 INSPECT MODAL LOGIC UPGRADED ---
        const inspectModal = document.getElementById('inspect-modal');
        const inspectContent = document.getElementById('inspect-modal-content');

        function inspectAgent(name, email, breakdown, totalScore) {
            document.getElementById('inspect-name').innerText = name;
            document.getElementById('inspect-email').innerText = email;
            document.getElementById('inspect-initial').innerText = name.charAt(0).toUpperCase();
            document.getElementById('inspect-total').innerText = totalScore;

            const breakdownContainer = document.getElementById('inspect-breakdown');
            breakdownContainer.innerHTML = '';

            breakdown.forEach(room => {
                let scoreColor = room.score > 0 ? 'text-emerald-400' : 'text-gray-500';
                let scoreText = room.score > 0 ? room.score + ' pts' : 'Pending';
                
                breakdownContainer.innerHTML += `
                    <div class="flex justify-between items-center p-4 md:p-5 bg-black/30 border border-purple-500/20 rounded-lg hover:bg-black/50 transition-colors">
                        <span class="text-gray-300 uppercase tracking-widest font-bold md:text-lg">${room.room_title}</span>
                        <span class="font-black md:text-xl ${scoreColor}">${scoreText}</span>
                    </div>
                `;
            });

            applyLanguage(currentLang);

            inspectModal.classList.remove('hidden');
            inspectModal.classList.add('flex');
            
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                inspectModal.classList.remove('opacity-0');
                inspectContent.classList.remove('scale-95');
                inspectContent.classList.add('scale-100');
            }, 10);
        }

        function closeInspectModal() {
            inspectModal.classList.add('opacity-0');
            inspectContent.classList.remove('scale-100');
            inspectContent.classList.add('scale-95');
            
            document.body.style.overflow = '';

            setTimeout(() => {
                inspectModal.classList.add('hidden');
                inspectModal.classList.remove('flex');
            }, 300);
        }
    </script>
    <script>
    // Tell the parent wrapper to ensure music is playing
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.parent.postMessage('ensureMusicPlaying', '*');
        }, 100);
    });
</script>
@include('partials.cursor')
</body>
</html>