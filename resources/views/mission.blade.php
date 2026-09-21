<!DOCTYPE html>
@php 
    session(['rooms_completed_memory' => $roomsCompleted ?? 0]); 
    $progress = auth()->check() ? \App\Models\GameProgress::where('user_id', auth()->id())->first() : null;
    
    $coreScore = 0;
    $arcadeScore = 0;
    $completedArcadeGames = collect();

    if($progress) {
        $coreScore = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score;
        $arcadeScore = $progress->level_6_score;

        $completedGameIds = $progress->completed_minigames ?? [];
        if (!empty($completedGameIds) && class_exists(\App\Models\MiniGame::class)) {
            $completedArcadeGames = \App\Models\MiniGame::whereIn('id', $completedGameIds)->get();
        }
    }
    $grandTotal = $coreScore + $arcadeScore;

    // --- RAW DATABASE MATH (BYPASSING MODELS ENTIRELY) ---
    $calcMax = 0;
    
    // 1. Core Modules
    $calcMax += \Illuminate\Support\Facades\DB::table('phishing_emails')->count() * 100;
    $calcMax += \Illuminate\Support\Facades\DB::table('level_two_questions')->count() * 100;
    $calcMax += \Illuminate\Support\Facades\DB::table('level_three_scenarios')->count() * 100;
    $calcMax += \Illuminate\Support\Facades\DB::table('level_four_scenarios')->count() * 100;

    // 2. Mainframe
    $level5Count = \Illuminate\Support\Facades\DB::table('level_five_questions')->count();
    $setting = \Illuminate\Support\Facades\DB::table('mainframe_settings')->first();
    $bonus = $setting ? $setting->streak_bonus_percent : 2;
    $calcMax += ($level5Count * 100) + (100 * ($bonus / 100) * (($level5Count * ($level5Count - 1)) / 2));

    // 3. Arcade (Force fetch as integer)
    $arcadeTotal = (int) \Illuminate\Support\Facades\DB::table('mini_games')->sum('base_score');
    $calcMax += $arcadeTotal;
    
    // Set the final variable
    $trueMaxScore = $calcMax;

    // 🔥 FETCH ALL ACTIVE CUSTOM ROOMS 🔥
    $customRooms = \App\Models\Room::where('is_active', 1)->get();
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Mission Control</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(16,185,129,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; 
        
        -ms-overflow-style: none; 
        scrollbar-width: none;}

        body::-webkit-scrollbar {
            display: none;
        }
    /* This hides the default cursor inside your iframes/sub-pages */
    body, a, button, input, select, textarea {
    cursor: none !important;
}
        :root {
            --bg-color: #000000; --text-color: #d1d5db; --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.3), rgba(0,0,0,0.9));
            --card-bg: rgba(21, 21, 21, 0.85); --card-hover: rgba(31, 31, 31, 0.9);
            --title-color: #ffffff; --value-color: #ffffff; --badge-text: #000000;
            --btn-bg: rgba(255, 255, 255, 0.9);
            --glossy-sheen: inset 0 1px 0 rgba(255,255,255,0.15); 
            
            /* DYNAMIC DESCRIPTION COLORS (DARK MODE) */
            --desc-safe-text: #d1d5db; 
            --desc-safe-strong: #34d399; 
            --desc-danger-text: #fca5a5; 
            --desc-danger-strong: #ef4444; 
        }

        .light-mode {
            --bg-color: #d1d5db; --text-color: #0f172a; --vid-opacity: 0.15; 
            --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
            --card-bg: rgba(229, 231, 235, 0.95); 
            --card-hover: rgba(243, 244, 246, 0.98);
            --title-color: #10b981; 
            --value-color: #0f172a; --badge-text: #ffffff;
            --btn-bg: rgba(15, 23, 42, 0.9); 
            --glossy-sheen: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
            
            /* DYNAMIC DESCRIPTION COLORS (LIGHT MODE) */
            --desc-safe-text: #374151; 
            --desc-safe-strong: #047857; 
            --desc-danger-text: #7f1d1d; 
            --desc-danger-strong: #b91c1c; 
        }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; box-shadow: var(--glossy-sheen); }
        .theme-card:hover { background: var(--card-hover) !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255,255,255,0.8); }
        .theme-title { color: var(--title-color) !important; }
        .theme-value { color: var(--value-color) !important; }
        .theme-badge { color: var(--badge-text) !important; }
        .theme-btn-bg { background-color: var(--btn-bg) !important; }

        .theme-replay { background-color: rgba(107,114,128,0.2); color: #9ca3af; border: 1px solid #6b7280; transition: all 0.3s; }
        .theme-replay:hover { background-color: #6b7280; color: #fff; }
        .light-mode .theme-replay { background-color: rgba(156,163,175,0.3); color: #1f2937; border-color: #6b7280; font-weight: 800; }
        .light-mode .theme-replay:hover { background-color: #6b7280; color: #fff; }

        .theme-replay-danger { background-color: rgba(17,24,39,0.8); color: #9ca3af; border: 1px solid #374151; transition: all 0.3s; }
        .theme-replay-danger:hover { background-color: rgba(127,29,29,0.8); color: #fff; border-color: #ef4444; }
        .light-mode .theme-replay-danger { background-color: rgba(254,226,226,0.8); color: #991b1b; border-color: #f87171; font-weight: 800; }
        .light-mode .theme-replay-danger:hover { background-color: #ef4444; color: #fff; }

        /* --- HOVER DESCRIPTION LOGIC --- */
        .room-card-wrapper { cursor: default; }
        .room-desc {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 0;
        }
        .room-card-wrapper:hover .room-desc {
            max-height: 200px;
            opacity: 1;
            margin-top: 1rem;
        }
        .room-card-wrapper:hover { transform: translateY(-2px); z-index: 10; }
        .room-card-emerald:hover { box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3); }
        .room-card-red:hover { box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.3); }
        .room-card-purple:hover { box-shadow: 0 10px 25px -5px rgba(168, 85, 247, 0.3); }

        .desc-safe { color: var(--desc-safe-text); transition: color 0.3s ease; }
        .desc-safe strong { color: var(--desc-safe-strong); font-weight: 800; transition: color 0.3s ease; }
        .desc-danger { color: var(--desc-danger-text); transition: color 0.3s ease; }
        .desc-danger strong { color: var(--desc-danger-strong); font-weight: 800; transition: color 0.3s ease; text-shadow: none; }

        .sc-anim { opacity: 0; animation-fill-mode: forwards; animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1); animation-duration: 0.6s; }
        .sc-in-left { animation-name: slideInLeft; } .sc-in-right { animation-name: slideInRight; }
        .sc-out-left { animation-name: slideOutLeft; animation-timing-function: cubic-bezier(0.7, 0, 0.84, 0); animation-duration: 0.5s; }
        .sc-out-right { animation-name: slideOutRight; animation-timing-function: cubic-bezier(0.7, 0, 0.84, 0); animation-duration: 0.5s; }

        .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; } .d-4 { animation-delay: 0.4s; } .d-5 { animation-delay: 0.5s; } .d-6 { animation-delay: 0.6s; }

        @keyframes slideInLeft { 0% { transform: translateX(-100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideInRight { 0% { transform: translateX(100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutLeft { 0% { transform: translateX(0); opacity: 1; } 100% { transform: translateX(-100vw); opacity: 0; } }
        @keyframes slideOutRight { 0% { transform: translateX(0); opacity: 1; } 100% { transform: translateX(100vw); opacity: 0; } }

        .lz-input { background-color: #0a0a0a; border: 1px solid #065f46; color: #d1d5db; width: 100%; padding: 10px 12px; border-radius: 4px; font-family: 'Share Tech Mono', monospace; font-size: 0.875rem; text-align: center; letter-spacing: 0.1em; transition: all 0.2s; }
        .lz-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }
        .light-mode .lz-input { background-color: #ffffff; color: #111827; border-color: #9ca3af; }

        .rank-card-glow {
            border: 2px solid var(--rank-color);
            animation: rankPulse 3s ease-in-out infinite;
            position: relative;
        }
        @keyframes rankPulse {
            0%, 100% { box-shadow: 0 0 10px var(--rank-color-half), inset 0 0 10px var(--rank-color-half); }
            50% { box-shadow: 0 0 25px var(--rank-color), inset 0 0 15px var(--rank-color-half); }
        }
        
        .btn-blink { animation: slowBlink 2.5s ease-in-out infinite; }
        .btn-blink:hover { animation: none; opacity: 1; filter: brightness(1); }
        @keyframes slowBlink { 0%, 100% { opacity: 1; filter: brightness(1); } 50% { opacity: 0.5; filter: brightness(0.8); } }

        /* 🔥 CUSTOM SHINING CARD STYLES 🔥 */
        .shining-card {
            border: 1px solid transparent;
            background-image: linear-gradient(var(--card-bg), var(--card-bg)), linear-gradient(135deg, #10b981, #d4af37, #10b981);
            background-origin: border-box;
            background-clip: padding-box, border-box;
            animation: customShine 4s ease-in-out infinite;
        }
        @keyframes customShine {
            0%, 100% { box-shadow: 0 0 15px rgba(16,185,129,0.3), inset 0 0 10px rgba(212,175,55,0.1); }
            50% { box-shadow: 0 0 30px rgba(212,175,55,0.6), inset 0 0 20px rgba(16,185,129,0.3); }
        }
        .text-emerald-gold {
            background: linear-gradient(to right, #34d399, #f0d56f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

@php
    function getRankDetails($rank) {
        if (!is_numeric($rank)) return ['title' => 'Agent', 'color' => '#8b5cf6'];
        
        return match (true) {
            $rank == 1 => ['title' => 'Cyber General', 'color' => '#eab308'], 
            $rank == 2 => ['title' => 'Cyber Lieutenant General', 'color' => '#f59e0b'], 
            $rank == 3 => ['title' => 'Cyber Major General', 'color' => '#d97706'], 
            $rank == 4 => ['title' => 'Cyber Brigadier General', 'color' => '#ea580c'], 
            $rank == 5 => ['title' => 'Cyber Colonel', 'color' => '#ef4444'], 
            $rank == 6 => ['title' => 'Cyber Lieutenant Colonel', 'color' => '#8b5cf6'], 
            $rank == 7 => ['title' => 'Cyber Major', 'color' => '#3b82f6'], 
            $rank == 8 => ['title' => 'Cyber Captain', 'color' => '#10b981'], 
            $rank == 9 => ['title' => 'Cyber Lieutenant', 'color' => '#84cc16'], 
            $rank == 10 => ['title' => 'Cyber Second Lieutenant', 'color' => '#78716c'], 
            default => ['title' => 'Agent', 'color' => '#8b5cf6'], 
        };
    }
    
    $userRank = \App\Models\GameProgress::where('total_score', '>', \App\Models\GameProgress::where('user_id', auth()->id())->value('total_score') ?? -1)->count() + 1;
    $userRankDetails = getRankDetails($userRank);
@endphp

<body class="min-h-screen relative overflow-x-hidden transition-colors duration-500 pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-50 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-6">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors shadow-[0_0_10px_rgba(16,185,129,0.3)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors shadow-[0_0_10px_rgba(16,185,129,0.3)]">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-8 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-emerald-500/30 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-[0_0_20px_rgba(220,38,38,0.6)] animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h1 class="theme-title title-font text-3xl tracking-wider uppercase mb-1 text-red-500" data-en="ACTIVE OPERATION" data-ms="OPERASI AKTIF">ACTIVE OPERATION</h1>
                    
                    <p class="font-bold tracking-widest text-sm uppercase transition-colors" 
                       style="color: {{ $userRankDetails['color'] }}; text-shadow: 0 0 8px {{ $userRankDetails['color'] }}80;"
                       data-en="{{ $userRankDetails['title'] }} {{ auth()->check() ? auth()->user()->name : 'Ahmad Hanif' }} Deployed" 
                       data-ms="{{ $userRankDetails['title'] }} {{ auth()->check() ? auth()->user()->name : 'Ahmad Hanif' }} Dikerahkan">
                       {{ $userRankDetails['title'] }} {{ auth()->check() ? auth()->user()->name : 'Ahmad Hanif' }} Deployed
                    </p>
                </div>
            </div>
            
            <a href="{{ route('dashboard') }}" id="nav-return" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-emerald-500/50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Abort / Return" data-ms="Batal / Kembali">Abort / Return</span>
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="flex flex-col gap-6 sc-anim sc-in-left d-2">
                <div class="theme-card backdrop-blur-md rounded-lg p-8 border border-red-500/40 shadow-[0_0_25px_rgba(220,38,38,0.15)] h-fit">
                    <div class="flex items-center text-red-500 text-sm mb-5 border-b border-red-500/20 pb-4 font-bold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span data-en="Mission Briefing" data-ms="Taklimat Misi">Mission Briefing</span>
                    </div>
                    
                    <div class="font-mono text-base md:text-lg font-bold leading-relaxed opacity-95 space-y-5 theme-value">
                        <p data-en="LZNK systems are under threat. A coordinated social engineering attack has bypassed our perimeter defenses." data-ms="Sistem LZNK sedang diancam. Serangan kejuruteraan sosial yang terancang telah melepasi lapisan pertahanan perimeter kita.">
                            LZNK systems are under threat. A coordinated social engineering attack has bypassed our perimeter defenses.
                        </p>
                        <p class="text-emerald-500 font-extrabold text-lg md:text-xl" data-en="Your Objective:" data-ms="Objektif Anda:">Your Objective:</p>
                        <ul class="list-disc pl-5 space-y-3 font-bold">
                            <li data-en="Identify and neutralize the incoming loads of communications." data-ms="Kenalpasti dan selamatkan sistem komunikasi yang dipintas.">Identify and neutralize the incoming loads of communications.</li>
                            <li data-en="Identify the phishing elements." data-ms="Kenal pasti unsur-unsur pancingan data (phishing).">Identify the phishing elements.</li>
                            <li data-en="Secure the compromised modules to obtain all the lost word fragments to unlock your own program certificate as a reward." data-ms="Selamatkan modul yang terjejas untuk mendapatkan sijil program anda sebagai ganjaran.">Secure the compromised modules to obtain all the lost word fragments to unlock your own program certificate as a reward.</li>
                        </ul>
                         <p class="text-red-400 mt-4 animate-pulse font-bold" data-en="Failure is not an option." data-ms="Kegagalan bukan satu pilihan.">Failure is not an option.</p>

                    </div>
                </div>

                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.1)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center text-emerald-500 text-xs mb-3 font-bold tracking-widest uppercase">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                            <span data-en="Decrypted Fragments" data-ms="Serpihan Nyahsulit">Decrypted Fragments</span>
                        </div>
                        <div class="bg-black/50 rounded p-4 border border-emerald-900/50 font-mono text-center flex justify-center items-center gap-1 md:gap-2">
                            
                            @if(isset($progress) && $progress->level_1_completed)
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">R</span>
                            @else
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                            @endif

                            @if(isset($progress) && $progress->level_2_completed)
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">E</span>
                            @else
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                            @endif

                            @if(isset($progress) && $progress->level_3_completed)
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">C</span>
                            @else
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                            @endif

                            @if(isset($progress) && $progress->level_4_completed)
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">H</span>
                            @else
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                            @endif

                            @if(isset($progress) && $progress->level_5_completed)
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">E</span>
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">C</span>
                                <span class="text-emerald-500 font-bold text-xl md:text-2xl tracking-widest bg-emerald-500/10 px-3 py-1.5 rounded drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">K</span>
                            @else
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                                <span class="text-gray-600 font-bold text-xl md:text-2xl tracking-widest bg-gray-900/30 px-3 py-1.5 rounded">_</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex-1 flex flex-col justify-end">
                        @if(isset($progress) && $progress->level_5_completed)
                            <p class="text-yellow-500 text-xs md:text-sm font-bold text-center opacity-100 uppercase tracking-widest mb-4" data-en="Congratulations upon your successful completion of this mission. Here is the program certificate as a token of appreciation!" data-ms="Tahniah atas kejayaan anda menyelesaikan misi ini. Berikut adalah sijil program sebagai tanda penghargaan!">
                                Congratulations upon your successful completion of this mission. Here is the program certificate as a token of appreciation!
                            </p>
                            <a href="{{ route('agent.certificate') }}" class="nav-trigger w-full bg-yellow-500 hover:bg-yellow-400 text-black border border-yellow-300 font-extrabold py-3 px-6 rounded transition-all uppercase tracking-widest text-xs md:text-sm shadow-[0_0_15px_rgba(234,179,8,0.4)] hover:shadow-[0_0_25px_rgba(234,179,8,0.7)] flex items-center justify-center gap-2 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span data-en="VIEW CERTIFICATE" data-ms="LIHAT SIJIL">VIEW CERTIFICATE</span>
                            </a>
                        @else
                            <p class="theme-value text-xs md:text-sm font-bold text-center opacity-70 uppercase tracking-widest" data-en="Find all pieces to unlock the mainframe." data-ms="Cari semua serpihan untuk membuka komputer utama.">
                                Find all pieces to unlock the mainframe.
                            </p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="lg:col-span-2 flex flex-col gap-4">
                
                {{-- ROOM 1 --}}
                <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 border {{ isset($progress) && $progress->level_1_completed ? 'border-emerald-500/60 shadow-[0_0_15px_rgba(16,185,129,0.2)] room-card-emerald' : 'border-gray-600/30 opacity-75' }} relative overflow-hidden sc-anim sc-in-right d-1">
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                        <div class="flex items-center gap-4 w-full {{ isset($progress) && $progress->level_1_completed ? '' : 'opacity-60' }}">
                            <div class="w-10 h-10 rounded {{ isset($progress) && $progress->level_1_completed ? 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/50' : 'bg-gray-600/20 text-gray-500 border border-gray-500/50' }} flex items-center justify-center shrink-0">
                                <span class="font-bold text-lg">01</span>
                            </div>
                            <div>
                                <h3 class="theme-value title-font text-xl mb-0.5" data-en="The Phishing Net" data-ms="Jaring Phishing">The Phishing Net</h3>
                                @if(isset($progress) && $progress->level_1_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: CLEARED" data-ms="STATUS: SELESAI">STATUS: CLEARED</p>
                                @else
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: UNLOCKED" data-ms="STATUS: TERBUKA">STATUS: UNLOCKED</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2 w-full md:w-auto shrink-0 justify-center">
                            @if(isset($progress) && $progress->level_1_completed)
                                <a href="{{ route('agent.level1') }}" class="nav-trigger btn-blink w-full theme-replay font-bold py-2 px-6 rounded uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="REPLAY" data-ms="MAIN SEMULA">REPLAY</span>
                                </a>
                                <a href="{{ route('agent.certificate', ['room' => 1]) }}" class="w-full bg-emerald-900/40 hover:bg-emerald-600 text-emerald-400 hover:text-white font-bold py-1.5 px-4 rounded text-center uppercase tracking-widest text-[10px] transition-colors border border-emerald-500/50 flex justify-center items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span data-en="PRINT CERTIFICATE" data-ms="CETAK SIJIL">PRINT CERTIFICATE</span>
                                </a>
                            @else
                                <a href="{{ route('agent.level1') }}" class="nav-trigger btn-blink w-full bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-black border border-emerald-500 font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_10px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span data-en="ENTER" data-ms="MASUK">ENTER</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($progress) && $progress->level_1_completed)
                    <div class="room-desc relative z-10 border-t border-emerald-500/30 pt-3">
                        <p class="desc-safe text-xs font-mono" data-en="<strong>OBJECTIVE:</strong> Assess and strengthen your password integrity knowledge against automated dictionary and brute-force attacks to retrieve Fragment 2." data-ms="<strong>OBJEKTIF:</strong> Nilai dan kukuhkan pengetahuan anda mengenai pemerkasaan kata laluan terhadap serangan kamus (dictionary) dan brute-force automatik untuk mendapatkan Serpihan 2.">
                            <strong>OBJECTIVE:</strong> Assess and strengthen your password integrity knowledge against automated dictionary and brute-force attacks to retrieve Fragment 2.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- ROOM 2 --}}
                <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 border {{ isset($progress) && $progress->level_2_completed ? 'border-emerald-500/60 shadow-[0_0_15px_rgba(16,185,129,0.2)] room-card-emerald' : 'border-gray-600/30 opacity-75' }} relative overflow-hidden sc-anim sc-in-right d-2">
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                        <div class="flex items-center gap-4 w-full {{ isset($progress) && $progress->level_2_completed ? '' : 'opacity-60' }}">
                            <div class="w-10 h-10 rounded {{ isset($progress) && $progress->level_2_completed ? 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/50' : 'bg-gray-600/20 text-gray-500 border border-gray-500/50' }} flex items-center justify-center shrink-0">
                                <span class="font-bold text-lg">02</span>
                            </div>
                            <div>
                                <h3 class="theme-value title-font text-xl mb-0.5" data-en="The Brute Force Gate" data-ms="Pintu Brute Force">The Brute Force Gate</h3>
                                
                                @if(isset($progress) && $progress->level_2_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: CLEARED" data-ms="STATUS: SELESAI">STATUS: CLEARED</p>
                                @elseif(isset($progress) && $progress->level_1_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: UNLOCKED" data-ms="STATUS: TERBUKA">STATUS: UNLOCKED</p>
                                @else
                                    <p class="text-[10px] uppercase tracking-widest text-red-500 font-bold flex items-center gap-1" data-en="STATUS: LOCKED" data-ms="STATUS: DIKUNCI">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        STATUS: LOCKED
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2 w-full md:w-auto shrink-0 justify-center">
                            @if(isset($progress) && $progress->level_2_completed)
                                <a href="{{ route('agent.level2') }}" class="nav-trigger btn-blink w-full theme-replay font-bold py-2 px-6 rounded uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="REPLAY" data-ms="MAIN SEMULA">REPLAY</span>
                                </a>
                                <a href="{{ route('agent.certificate', ['room' => 2]) }}" class="w-full bg-emerald-900/40 hover:bg-emerald-600 text-emerald-400 hover:text-white font-bold py-1.5 px-4 rounded text-center uppercase tracking-widest text-[10px] transition-colors border border-emerald-500/50 flex justify-center items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span data-en="PRINT CERTIFICATE" data-ms="CETAK SIJIL">PRINT CERTIFICATE</span>
                                </a>
                            @elseif(isset($progress) && $progress->level_1_completed)
                                <a href="{{ route('agent.level2') }}" class="nav-trigger btn-blink w-full bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-black border border-emerald-500 font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_10px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span data-en="ENTER" data-ms="MASUK">ENTER</span>
                                </a>
                            @else
                                <div class="w-full border border-red-500/50 text-red-500 py-2.5 px-6 rounded text-center text-[10px] tracking-widest font-bold uppercase" data-en="CLEAR MOD 01" data-ms="SELESAIKAN MOD 01">
                                    CLEAR MOD 01
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($progress) && $progress->level_2_completed)
                    <div class="room-desc relative z-10 border-t border-emerald-500/30 pt-3">
                        <p class="desc-safe text-xs font-mono" data-en="<strong>OBJECTIVE:</strong> Evaluate the real-world behavioral manipulation. Decide how to respond to suspicious requests through social media and physical security breaches to secure Fragment 3." data-ms="<strong>OBJEKTIF:</strong> Nilai manipulasi tingkah laku yang berlaku di dunia nyata. Anda harus membuat keputusan dalam bertindak balas terhadap permintaan mencurigakan dan pelanggaran keselamatan fizikal di media sosial untuk mendapatkan Serpihan 3.">
                            <strong>OBJECTIVE:</strong> Evaluate the real-world behavioral manipulation. Decide how to respond to suspicious requests through social media and physical security breaches to secure Fragment 3.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- ROOM 3 --}}
                <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 border {{ isset($progress) && $progress->level_3_completed ? 'border-emerald-500/60 shadow-[0_0_15px_rgba(16,185,129,0.2)] room-card-emerald' : 'border-gray-600/30 opacity-75' }} relative overflow-hidden sc-anim sc-in-right d-3">
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                        <div class="flex items-center gap-4 w-full {{ isset($progress) && $progress->level_3_completed ? '' : 'opacity-60' }}">
                            <div class="w-10 h-10 rounded {{ isset($progress) && $progress->level_3_completed ? 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/50' : 'bg-gray-600/20 text-gray-500 border border-gray-500/50' }} flex items-center justify-center shrink-0">
                                <span class="font-bold text-lg">03</span>
                            </div>
                            <div>
                                <h3 class="theme-value title-font text-xl mb-0.5" data-en="The Human Firewall" data-ms="Tembok Api Manusia">The Human Firewall</h3>
                                
                                @if(isset($progress) && $progress->level_3_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: CLEARED" data-ms="STATUS: SELESAI">STATUS: CLEARED</p>
                                @elseif(isset($progress) && $progress->level_2_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: UNLOCKED" data-ms="STATUS: TERBUKA">
                                        STATUS: UNLOCKED
                                    </p>
                                @else
                                    <p class="text-[10px] uppercase tracking-widest text-red-500 font-bold flex items-center gap-1" data-en="STATUS: LOCKED" data-ms="STATUS: DIKUNCI">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        STATUS: LOCKED
                                    </p>
                                @endif
                                
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2 w-full md:w-auto shrink-0 justify-center">
                            @if(isset($progress) && $progress->level_3_completed)
                                <a href="{{ route('agent.level3') }}" class="nav-trigger btn-blink w-full theme-replay font-bold py-2 px-6 rounded uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="REPLAY" data-ms="MAIN SEMULA">REPLAY</span>
                                </a>
                                <a href="{{ route('agent.certificate', ['room' => 3]) }}" class="w-full bg-emerald-900/40 hover:bg-emerald-600 text-emerald-400 hover:text-white font-bold py-1.5 px-4 rounded text-center uppercase tracking-widest text-[10px] transition-colors border border-emerald-500/50 flex justify-center items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span data-en="PRINT CERTIFICATE" data-ms="CETAK SIJIL">PRINT CERTIFICATE</span>
                                </a>
                            @elseif(isset($progress) && $progress->level_2_completed)
                                <a href="{{ route('agent.level3') }}" class="nav-trigger btn-blink w-full bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-black border border-emerald-500 font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_10px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span data-en="ENTER" data-ms="MASUK">ENTER</span>
                                </a>
                            @else
                                <div class="w-full border border-red-500/50 text-red-500 py-2.5 px-6 rounded text-center text-[10px] tracking-widest font-bold uppercase" data-en="CLEAR MOD 02" data-ms="SELESAIKAN MOD 02">
                                    CLEAR MOD 02
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($progress) && $progress->level_3_completed)
                    <div class="room-desc relative z-10 border-t border-emerald-500/30 pt-3">
                        <p class="desc-safe text-xs font-mono" data-en="<strong>OBJECTIVE:</strong> Conduct forensic analysis on deceptive URLs and spoofed domains. Locate the fourth Fragment required to unlock the program certificate." data-ms="<strong>OBJEKTIF:</strong> Lakukan analisis forensik pada URL yang memperdaya dan domain yang dipalsukan. Cari Serpihan keempat yang diperlukan untuk membuka kunci bagi sijil program.">
                            <strong>OBJECTIVE:</strong> Conduct forensic analysis on deceptive URLs and spoofed domains. Locate the fourth Fragment required to unlock the program certificate.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- ROOM 4 --}}
                <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 border {{ isset($progress) && $progress->level_4_completed ? 'border-emerald-500/60 shadow-[0_0_15px_rgba(16,185,129,0.2)] room-card-emerald' : 'border-gray-600/30 opacity-75' }} relative overflow-hidden sc-anim sc-in-right d-4">
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                        <div class="flex items-center gap-4 w-full {{ isset($progress) && $progress->level_4_completed ? '' : 'opacity-60' }}">
                            <div class="w-10 h-10 rounded {{ isset($progress) && $progress->level_4_completed ? 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/50' : 'bg-gray-600/20 text-gray-500 border border-gray-500/50' }} flex items-center justify-center shrink-0">
                                <span class="font-bold text-lg">04</span>
                            </div>
                            <div>
                                <h3 class="theme-value title-font text-xl mb-0.5" data-en="The Mirror Web" data-ms="Jaringan Cermin">The Mirror Web</h3>
                                
                                @if(isset($progress) && $progress->level_4_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: CLEARED" data-ms="STATUS: SELESAI">STATUS: CLEARED</p>
                                @elseif(isset($progress) && $progress->level_3_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-500 font-bold flex items-center gap-1" data-en="STATUS: UNLOCKED" data-ms="STATUS: TERBUKA">
                                        STATUS: UNLOCKED
                                    </p>
                                @else
                                    <p class="text-[10px] uppercase tracking-widest text-red-500 font-bold flex items-center gap-1" data-en="STATUS: LOCKED" data-ms="STATUS: DIKUNCI">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        STATUS: LOCKED
                                    </p>
                                @endif
                                
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2 w-full md:w-auto shrink-0 justify-center">
                            @if(isset($progress) && $progress->level_4_completed)
                                <a href="{{ route('agent.level4') }}" class="nav-trigger btn-blink w-full theme-replay font-bold py-2 px-6 rounded uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="REPLAY" data-ms="MAIN SEMULA">REPLAY</span>
                                </a>
                                <a href="{{ route('agent.certificate', ['room' => 4]) }}" class="w-full bg-emerald-900/40 hover:bg-emerald-600 text-emerald-400 hover:text-white font-bold py-1.5 px-4 rounded text-center uppercase tracking-widest text-[10px] transition-colors border border-emerald-500/50 flex justify-center items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span data-en="PRINT CERTIFICATE" data-ms="CETAK SIJIL">PRINT CERTIFICATE</span>
                                </a>
                            @elseif(isset($progress) && $progress->level_3_completed)
                                <a href="{{ route('agent.level4') }}" class="nav-trigger btn-blink w-full bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-black border border-emerald-500 font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_10px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span data-en="ENTER" data-ms="MASUK">ENTER</span>
                                </a>
                            @else
                                <div class="w-full border border-red-500/50 text-red-500 py-2.5 px-6 rounded text-center text-[10px] tracking-widest font-bold uppercase" data-en="CLEAR MOD 03" data-ms="SELESAIKAN MOD 03">
                                    CLEAR MOD 03
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($progress) && $progress->level_4_completed)
                    <div class="room-desc relative z-10 border-t border-emerald-500/30 pt-3">
                        <p class="desc-safe text-xs font-mono" data-en="<strong>OBJECTIVE:</strong> Conduct forensic analysis on deceptive URLs and spoofed domains. Locate the fourth Fragment required to unlock the program certificate." data-ms="<strong>OBJEKTIF:</strong> Lakukan analisis forensik pada URL yang memperdaya dan domain yang dipalsukan. Cari Serpihan keempat yang diperlukan untuk membuka kunci bagi sijil program.">
                            <strong>OBJECTIVE:</strong> Conduct forensic analysis on deceptive URLs and spoofed domains. Locate the fourth Fragment required to unlock the program certificate.
                        </p>
                    </div>
                    @endif
                </div>


                {{-- ROOM 5 (MAINFRAME) --}}
                <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 border {{ isset($progress) && $progress->level_5_completed ? 'border-red-500 shadow-[0_0_20px_rgba(220,38,38,0.4)] room-card-red' : 'border-red-900/60 opacity-60 shadow-[0_0_20px_rgba(220,38,38,0.1)]' }} relative overflow-hidden sc-anim sc-in-right d-5 mt-4">
                    <div class="absolute inset-0 bg-red-900/10 z-0 pointer-events-none"></div>
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                        <div class="flex items-center gap-4 w-full {{ isset($progress) && $progress->level_5_completed ? 'opacity-100' : 'opacity-80' }}">
                            <div class="w-10 h-10 rounded bg-red-900/40 flex items-center justify-center text-red-500 border border-red-500/50 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" /></svg>
                            </div>
                            <div>
                                <h3 class="text-red-500 title-font text-xl mb-0.5 tracking-widest" data-en="S.H.I.E.L.D MAINFRAME" data-ms="MAINFRAME S.H.I.E.L.D">S.H.I.E.L.D MAINFRAME</h3>
                                
                                @if(isset($progress) && $progress->level_5_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-red-400 font-bold flex items-center gap-1" data-en="STATUS: BREACHED" data-ms="STATUS: DITEMBUSI">STATUS: BREACHED</p>
                                @elseif(isset($progress) && $progress->level_4_completed)
                                    <p class="text-[10px] uppercase tracking-widest text-red-400 font-bold flex items-center gap-1" data-en="STATUS: READY FOR OVERRIDE" data-ms="STATUS: BERSEDIA UNTUK PINTASAN">STATUS: READY FOR OVERRIDE</p>
                                @else
                                    <p class="text-[10px] uppercase tracking-widest text-red-600 font-bold flex items-center gap-1" data-en="STATUS: CRITICAL ENCRYPTION" data-ms="STATUS: ENKRIPSI KRITIKAL">STATUS: CRITICAL ENCRYPTION</p>
                                @endif
                                
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-2 w-full md:w-auto shrink-0 justify-center">
                            @if(isset($progress) && $progress->level_5_completed)
                                <a href="{{ route('agent.level5') }}" class="nav-trigger btn-blink w-full theme-replay-danger font-bold py-2 px-6 rounded uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="REPLAY SIMULATION" data-ms="MAIN SEMULA SIMULASI">REPLAY SIMULATION</span>
                                </a>
                                <a href="{{ route('agent.certificate', ['room' => 5]) }}" class="w-full bg-emerald-900/40 hover:bg-emerald-600 text-emerald-400 hover:text-white font-bold py-1.5 px-4 rounded text-center uppercase tracking-widest text-[10px] transition-colors border border-emerald-500/50 flex justify-center items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span data-en="PRINT CERTIFICATE" data-ms="CETAK SIJIL">PRINT CERTIFICATE</span>
                                </a>
                            @elseif(isset($progress) && $progress->level_4_completed)
                                <a href="{{ route('agent.level5') }}" class="nav-trigger btn-blink w-full bg-red-900/50 hover:bg-red-600 text-red-400 hover:text-white border border-red-500 font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_15px_rgba(220,38,38,0.5)] hover:shadow-[0_0_25px_rgba(220,38,38,0.8)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    <span data-en="INITIATE HACK" data-ms="MULAKAN PENGGODAMAN">INITIATE HACK</span>
                                </a>
                            @else
                                <div class="w-full border border-red-500/50 text-red-500 py-2.5 px-6 rounded text-center text-[10px] tracking-widest font-bold uppercase" data-en="REQUIRES 4 FRAGMENTS" data-ms="MEMERLUKAN 4 SERPIHAN">
                                    REQUIRES 4 FRAGMENTS
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($progress) && $progress->level_5_completed)
                    <div class="room-desc relative z-10 border-t border-red-500/30 pt-3">
                        <p class="desc-danger text-xs font-mono" data-en="<strong>WARNING: FINAL EXAM.</strong> A rapid-fire gauntlet testing all cumulative knowledge. You have limited time and only 3 mistakes permitted to secure your clearance to obtain the program certificate." data-ms="<strong>AMARAN: UJIAN AKHIR.</strong> Ujian pantas menguji semua pengetahuan terkumpul. Masa anda adalah terhad dan hanya 3 ralat dibenarkan untuk mendapatkan akses kepada sijil program.">
                            <strong>WARNING: FINAL EXAM.</strong>A rapid-fire gauntlet testing all cumulative knowledge. You have limited time and only 3 mistakes permitted to secure your clearance to obtain the program certificate.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- SPECIAL OPERATIONS HUB (BRANCHING MODULES) --}}
                <div class="sc-anim sc-in-right d-5 mt-4">
                    <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 relative overflow-hidden shining-card">
                        <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                            <div class="flex items-center gap-4 w-full">
                                <div class="w-10 h-10 rounded bg-gradient-to-br from-emerald-500 to-yellow-500 flex items-center justify-center text-[#0f2818] font-black text-lg shrink-0 border border-yellow-300 shadow-[0_0_10px_rgba(212,175,55,0.8)]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                </div>
                                <div>
                                    <h3 class="title-font text-xl mb-0.5 text-emerald-gold" data-en="BRANCHING GAME OPERATIONS HUB" data-ms="HAB OPERASI BRANCHING GAME">BRANCHING GAME OPERATIONS HUB</h3>
                                    <p class="text-[10px] uppercase tracking-widest text-emerald-400 font-bold flex items-center gap-1" data-en="STATUS: UNLOCKED" data-ms="STATUS: TERBUKA">STATUS: UNLOCKED</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-2 w-full md:w-auto shrink-0 justify-center">
                                <a href="{{ route('agent.branching-hub') }}" class="nav-trigger btn-blink w-full md:w-auto bg-gradient-to-r from-emerald-600 to-yellow-600 hover:from-emerald-500 hover:to-yellow-500 text-white font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_15px_rgba(16,185,129,0.5)] hover:shadow-[0_0_25px_rgba(212,175,55,0.8)] flex items-center justify-center gap-2 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span data-en="ENTER HUB" data-ms="MASUK HAB">ENTER HUB</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="room-desc relative z-10 border-t border-emerald-500/30 pt-3">
                            <p class="desc-safe text-xs font-mono text-gray-300" data-en="Access a classified network of dynamic branching scenarios to test your decision-making skills." data-ms="Akses rangkaian sulit senario bercabang dinamik untuk menguji kemahiran membuat keputusan anda.">
                                Access a classified network of dynamic branching scenarios to test your decision-making skills.
                            </p>
                            <div class="mt-3 flex items-center gap-2 text-[10px] text-yellow-500 font-bold uppercase tracking-wider">
                                <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                <span data-en="{{ $customRooms->count() }} ACTIVE MODULE(S)" data-ms="{{ $customRooms->count() }} MODUL AKTIF">{{ $customRooms->count() }} ACTIVE MODULE(S)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ARCADE --}}
                <div class="room-card-wrapper theme-card backdrop-blur-md rounded-lg p-5 border border-purple-500/60 shadow-[0_0_15px_rgba(168,85,247,0.2)] room-card-purple sc-anim sc-in-right d-6 relative overflow-hidden mt-4">
                    <div class="absolute inset-0 bg-purple-900/10 z-0 pointer-events-none"></div>
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative z-10">
                        <div class="flex items-center gap-4 w-full">
                            <div class="w-10 h-10 rounded bg-purple-500/20 flex items-center justify-center text-purple-400 border border-purple-500/50 shrink-0">
                                <span class="font-bold text-lg">06</span>
                            </div>
                            <div>
                                <h3 class="text-purple-400 title-font text-xl mb-0.5 tracking-widest" data-en="MINI-GAME ARCADE" data-ms="ARKED PERMAINAN MINI">MINI-GAME ARCADE</h3>
                                <p class="text-[10px] uppercase tracking-widest text-purple-500 font-bold flex items-center gap-1" data-en="STATUS: UNLOCKED" data-ms="STATUS: TERBUKA">STATUS: UNLOCKED</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('agent.arcade') }}" class="nav-trigger btn-blink w-full md:w-auto bg-purple-900/50 hover:bg-purple-600 text-purple-300 hover:text-white border border-purple-500 font-bold py-2 px-6 rounded transition-all uppercase tracking-widest text-xs shadow-[0_0_15px_rgba(168,85,247,0.5)] hover:shadow-[0_0_25px_rgba(168,85,247,0.8)] flex items-center justify-center gap-2 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span data-en="ENTER ARCADE" data-ms="MASUK ARKED">ENTER ARCADE</span>
                        </a>
                    </div>
                    
                    <div class="room-desc relative z-10 border-t border-purple-500/30 pt-3">
                        <p class="desc-safe text-xs font-mono text-purple-300" data-en="<strong class='text-purple-400'>OBJECTIVE:</strong> Complete various diverse interactive modules deployed by command to boost your overall score." data-ms="<strong class='text-purple-400'>OBJEKTIF:</strong> Selesaikan pelbagai modul interaktif yang dikerahkan oleh arahan untuk meningkatkan skor keseluruhan anda.">
                            <strong class='text-purple-400'>OBJECTIVE:</strong> Complete various diverse interactive modules deployed by command to boost your overall score.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <script>
        // --- UI CLICK SOUND LOGIC ---
        const clickSound = document.getElementById('ui-click-sound');
        if (clickSound) {
            clickSound.volume = 0.6;
            document.addEventListener('click', function(e) {
                // Play sound if a button, anchor link, or an element inside them was clicked
                if (e.target.closest('a, button')) {
                    clickSound.currentTime = 0;
                    clickSound.play().catch(err => console.log("Audio play blocked:", err));
                }
            });
        }

        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        // --- THE MAGIC: Start entrance transition instantly on load ---
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { document.body.classList.add('loaded'); }, 50); 
        });

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            translatables.forEach(el => {
                if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'DIV' || el.tagName === 'LI') {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan && !el.hasAttribute('data-en')) textSpan.innerText = el.getAttribute(`data-${lang}`);
                        else el.innerHTML = el.getAttribute(`data-${lang}`);
                    }
                }
            });
        }
        applyLanguage(currentLang);

        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                if(currentTheme === 'light') {
                    bodyEl.classList.add('light-mode');
                    themeBtn.innerHTML = '🌙 DARK MODE';
                } else {
                    bodyEl.classList.remove('light-mode');
                    themeBtn.innerHTML = '☀️ LIGHT MODE';
                }
            });
        }

        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        // --- NEW EXIT LOGIC ---
        document.querySelectorAll('.nav-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault(); 
                const targetUrl = this.getAttribute('href');
                
                document.body.classList.remove('loaded');
                document.body.classList.add('exiting');
                
                setTimeout(() => { window.location.href = targetUrl; }, 500); 
            });
        });
    </script>
<script>
    // Tell the parent wrapper to ensure music is playing
    // If it's already playing, the wrapper will ignore this and let it loop seamlessly.
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.parent.postMessage('ensureMusicPlaying', '*');
        }, 100);
    });
</script>
@include('partials.cursor')
</body>
</html>