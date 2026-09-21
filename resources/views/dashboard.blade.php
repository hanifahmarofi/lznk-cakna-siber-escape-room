<!DOCTYPE html>
@php 
    session(['rooms_completed_memory' => $roomsCompleted ?? 0]); 
    $progress = auth()->check() ? \App\Models\GameProgress::where('user_id', auth()->id())->first() : null;
    
    $coreScore = 0;
    $arcadeScore = 0;
    $branchingScore = 0;
    $completedArcadeGames = collect();
    $customRooms = collect();
    $customScoresArray = [];

    if($progress) {
        $coreScore = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score;
        $arcadeScore = $progress->level_6_score;

        $customScoresArray = is_string($progress->custom_room_scores) ? json_decode($progress->custom_room_scores, true) : ($progress->custom_room_scores ?? []);
        $branchingScore = array_sum($customScoresArray);

        if (class_exists(\App\Models\Room::class)) {
            $customRooms = \App\Models\Room::where('is_active', 1)->get();
        }

        $completedGameIds = $progress->completed_minigames ?? [];
        if (!empty($completedGameIds) && class_exists(\App\Models\MiniGame::class)) {
            $completedArcadeGames = \App\Models\MiniGame::whereIn('id', $completedGameIds)->get();
        }
    }
    
    $grandTotal = $coreScore + $arcadeScore;

    $calcMax = 0;
    $calcMax += \Illuminate\Support\Facades\DB::table('phishing_emails')->count() * 100;
    $calcMax += \Illuminate\Support\Facades\DB::table('level_two_questions')->count() * 100;
    $calcMax += \Illuminate\Support\Facades\DB::table('level_three_scenarios')->count() * 100;
    $calcMax += \Illuminate\Support\Facades\DB::table('level_four_scenarios')->count() * 100;

    $level5Count = \Illuminate\Support\Facades\DB::table('level_five_questions')->count();
    $setting = \Illuminate\Support\Facades\DB::table('mainframe_settings')->first();
    $bonus = $setting ? $setting->streak_bonus_percent : 2;
    $calcMax += ($level5Count * 100) + (100 * ($bonus / 100) * (($level5Count * ($level5Count - 1)) / 2));

    $arcadeTotal = (int) \Illuminate\Support\Facades\DB::table('mini_games')->sum('base_score');
    $calcMax += $arcadeTotal;
    
    $trueMaxScore = $calcMax;

    if (isset($leaderboard)) {
        $leaderboard = collect($leaderboard)->filter(function($entry) {
            return $entry->user !== null;
        })->values();
    }
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Mission Control</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;600;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: var(--bg-color); color: var(--text-color); font-family: 'Share Tech Mono', monospace; overflow-x: hidden; transition: background-color 0.3s ease; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(16,185,129,0.7);
    
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
            --bg-color: #050505; --text-color: #d1d5db; 
            --btn-bg: rgba(16, 185, 129, 0.1); --btn-text: #6ee7b7;
            --toggle-bg: rgba(16, 185, 129, 0.1); --toggle-text: #10b981; --toggle-hover-bg: rgba(16, 185, 129, 0.2); --toggle-border: rgba(16, 185, 129, 0.5);
            --card-bg: rgba(21, 21, 21, 0.85); --card-hover: rgba(31, 31, 31, 0.9);
            --title-color: #ffffff; --value-color: #ffffff; 
            --glossy-sheen: inset 0 1px 0 rgba(255,255,255,0.15); 
            --vid-opacity: 0.8; --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.3), rgba(0,0,0,0.9));
            --tab-branching-bg: #fde047; --tab-branching-text: #422006; --score-branching-text: #fde047;
        }
        
        .light-mode {
            --bg-color: #f8fafc; --text-color: #0f172a;
            --btn-bg: rgba(239, 253, 244, 0.8); --btn-text: #064e3b; 
            --toggle-bg: rgba(255, 255, 255, 0.9); --toggle-text: #0f172a; --toggle-hover-bg: #e2e8f0; --toggle-border: #94a3b8;
            --card-bg: rgba(229, 231, 235, 0.95); --card-hover: rgba(243, 244, 246, 0.98);
            --title-color: #10b981; --value-color: #0f172a;
            --glossy-sheen: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
            --vid-opacity: 0.15; --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
            --tab-branching-bg: #b45309; --tab-branching-text: #ffffff; --score-branching-text: #b45309;
        }
        
        .light-mode .text-gray-300, .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #000000 !important; }
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-emerald-500 { color: #047857 !important; }
        .light-mode .text-emerald-400 { color: #059669 !important; } 
        
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; pointer-events: none; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; box-shadow: var(--glossy-sheen); backdrop-filter: blur(8px); overflow: visible; }
        .theme-card:hover { background: var(--card-hover) !important; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255,255,255,0.8); }
        .theme-title { color: var(--title-color) !important; }
        .theme-value { color: var(--value-color) !important; }
        .theme-btn-bg { background-color: var(--btn-bg) !important; }

        .terminal-box { 
            background: var(--terminal-bg); 
            border: 1px solid var(--terminal-border); 
            box-shadow: var(--terminal-shadow); 
            border-radius: 8px; 
            position: relative; 
            transition: all 0.3s ease;
            overflow: visible; /* Changed from hidden to visible */
        }

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

        .rank-card-glow { border: 2px solid var(--rank-color); animation: rankPulse 3s ease-in-out infinite; position: relative; }
        @keyframes rankPulse { 0%, 100% { box-shadow: 0 0 10px var(--rank-color-half), inset 0 0 10px var(--rank-color-half); } 50% { box-shadow: 0 0 25px var(--rank-color), inset 0 0 15px var(--rank-color-half); } }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(100,100,100,0.5); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--text-color); }

        .modal-maximized { max-width: 95vw !important; height: 95vh !important; display: flex !important; flex-direction: column !important; }
        .modal-maximized .tab-content-wrapper { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .modal-maximized .modal-body-scroll { max-height: none !important; flex-grow: 1; font-size: 1.25rem !important; padding-right: 1rem; }
        .modal-maximized .modal-body-scroll > div { padding-bottom: 1rem; margin-bottom: 1rem; }
        .modal-maximized .total-row { font-size: 1.5rem !important; padding-top: 1.5rem; margin-top: auto; }
        @media (min-width: 768px) { .modal-maximized .modal-body-scroll { font-size: 1.8rem !important; } .modal-maximized .total-row { font-size: 2.2rem !important; } .modal-maximized h3 { font-size: 2rem !important; margin-bottom: 2rem !important; } }
    
        
        /* --- CUSTOM INFO TOOLTIP --- */
        .info-icon-wrapper { 
            position: relative; /* Changed from absolute to flow with text */
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            cursor: help; 
            margin-left: 8px;
            vertical-align: middle;
        }

        .info-tooltip-box { 
            position: absolute; 
            bottom: 150%; /* Pushed higher up */
            left: 50%; 
            transform: translateX(-50%) translateY(10px); 
            width: 320px; /* Made larger */
            padding: 1.5rem; 
            background: var(--card-bg); 
            border: 1px solid #10b981; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.8); 
            border-radius: 0.75rem; 
            opacity: 0; 
            visibility: hidden; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            pointer-events: none; 
            text-align: center; 
            backdrop-filter: blur(20px); 
            z-index: 99999; 
        }

        .info-tooltip-box::after { 
            content: ''; 
            position: absolute; 
            top: 100%; 
            left: 50%; 
            transform: translateX(-50%); 
            border-width: 8px; 
            border-style: solid; 
            border-color: #10b981 transparent transparent transparent; 
        }

        .info-icon-wrapper:hover .info-tooltip-box { 
            opacity: 1; 
            visibility: visible; 
            transform: translateX(-50%) translateY(0); 
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
    $userRankNum = \App\Models\GameProgress::has('user')->where('total_score', '>', \App\Models\GameProgress::where('user_id', auth()->id())->value('total_score') ?? -1)->count() + 1;
    $userRankDetails = getRankDetails($userRankNum);
@endphp

<body class="min-h-screen relative overflow-x-hidden transition-colors duration-500 pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-50 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-6">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-8 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 pb-4 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <img src="{{ asset('img/logo2.png') }}" alt="LZNK Logo" class="h-16 md:h-20 w-auto object-contain filter drop-shadow-[0_0_15px_rgba(255,215,0,0.8)]">
                <div>
                    <h1 class="theme-title title-font text-4xl tracking-wider uppercase mb-1" data-en="Mission Control" data-ms="Pusat Kawalan">Mission Control</h1>
                    <p class="font-bold tracking-widest text-sm uppercase transition-colors" style="color: {{ $userRankDetails['color'] }}; text-shadow: 0 0 8px {{ $userRankDetails['color'] }}80;" data-en="Welcome back, {{ $userRankDetails['title'] }} {{ auth()->check() ? auth()->user()->name : '' }}" data-ms="Selamat kembali, {{ $userRankDetails['title'] }} {{ auth()->check() ? auth()->user()->name : '' }}">
                        Welcome back, {{ $userRankDetails['title'] }} {{ auth()->check() ? auth()->user()->name : '' }}
                    </p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-purple-500/50 text-purple-500 text-xs font-bold tracking-widest uppercase" data-en="Staff Clearance" data-ms="Akses Staf">
                Staff Clearance
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <div class="sc-anim sc-in-left d-2">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 rank-card-glow transition-all duration-300 h-full" style="--rank-color: {{ $userRankDetails['color'] }}; --rank-color-half: {{ $userRankDetails['color'] }}60;">
                    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background: radial-gradient(circle at top right, var(--rank-color), transparent 70%);"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="w-12 h-12 rounded flex items-center justify-center shrink-0 border" style="background-color: var(--rank-color-half); color: var(--rank-color); border-color: var(--rank-color);">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </div>
                        <span class="font-bold text-5xl title-font drop-shadow-md" style="color: var(--rank-color);">#{{ $userRankNum }}</span>
                    </div>
                    <div class="relative z-10">
                        <p class="theme-value text-xl font-bold uppercase tracking-widest mb-1 opacity-90" data-en="Current Rank" data-ms="Pangkat Semasa">Current Rank</p>
                        <p class="text-3xl md:text-4xl font-extrabold truncate transition-colors" style="color: var(--rank-color); text-shadow: 0 0 10px var(--rank-color-half);" data-en="{{ $userRankDetails['title'] }}" data-ms="{{ $userRankDetails['title'] }}">{{ $userRankDetails['title'] }}</p>
                    </div>
                </div>
            </div>

            <button onclick="openScoreModal()" class="w-full text-left theme-card backdrop-blur-md rounded-lg p-6 border border-emerald-500/60 transition-all duration-300 sc-anim sc-in-left d-3 hover:border-emerald-400 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] relative group hover:z-50 cursor-pointer block">
                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-5 w-5 text-emerald-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded theme-btn-bg bg-opacity-10 flex items-center justify-center text-emerald-500 shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <span class="text-emerald-500 font-bold text-5xl title-font drop-shadow-sm">{{ $grandTotal }}</span>
                </div>
                <div>
                    <p class="theme-value text-xl font-bold uppercase tracking-widest mb-1 opacity-90" data-en="Total Score" data-ms="Jumlah Markah">Total Score</p>
                    <!-- Inside your Total Score card -->
<div class="flex items-center gap-3 flex-wrap">
    <p class="text-emerald-500 font-extrabold text-2xl m-0">
        {{ $grandTotal }} <span class="text-gray-500 font-normal text-lg">/ {{ $trueMaxScore }}</span>
    </p>
    
    <!-- INFO TOOLTIP -->
    <div class="info-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500/60 hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="info-tooltip-box">
            <p class="font-mono text-sm leading-relaxed normal-case tracking-normal theme-value">
                Scores may exceed the limit due to Room 5 Streak Multiplier bonuses.
            </p>
        </div>
    </div>
</div>
            </button>

            <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-blue-500/60 transition-all duration-300 sc-anim sc-in-right d-2">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded theme-btn-bg bg-opacity-10 flex items-center justify-center text-blue-500 shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-blue-500 font-bold text-5xl title-font drop-shadow-sm">{{ $roomsCompleted ?? 0 }}/5</span>
                </div>
                <div>
                    <p class="theme-value text-xl font-bold uppercase tracking-widest mb-1 opacity-90" data-en="Rooms Solved" data-ms="Bilik Selesai">Rooms Solved</p>
                    <p class="theme-value text-5xl font-extrabold">{{ $roomsCompleted ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Added hover:z-50 here -->
            <div class="theme-card backdrop-blur-md rounded-lg p-8 border border-emerald-500/40 lg:col-span-2 sc-anim sc-in-left d-4 relative hover:z-50">
                <div class="flex justify-between items-center mb-7 border-b border-emerald-500/20 pb-4">
                    <div class="flex items-center text-emerald-500 text-base font-bold tracking-widest uppercase">
                        <svg class="h-6 w-6 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span data-en="Global Leaderboard" data-ms="Papan Pendahulu Global">Global Leaderboard</span>
                        
                        <!-- NEW CLEAN TOOLTIP -->
                        <div class="info-icon-wrapper ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500/60 hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="info-tooltip-box">
                                <div class="w-8 h-8 mx-auto rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="font-mono text-sm leading-relaxed normal-case tracking-normal theme-value" data-en="Scores may exceed the limit due to Room 5 Streak Multiplier bonuses." data-ms="Markah mungkin melebihi had disebabkan oleh bonus Pengganda (Streak) di Bilik 5.">Scores may exceed the limit due to Room 5 Streak Multiplier bonuses.</p>
                            </div>
                        </div>
                    </div>

                    @if(isset($leaderboard) && count($leaderboard) > 5)
                    <div class="flex gap-3">
                        <button id="prev-lb-btn" class="hidden text-[10px] md:text-xs text-emerald-600 hover:text-emerald-400 transition-colors uppercase font-bold tracking-widest items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            <span data-en="PREV" data-ms="KEMBALI">PREV</span>
                        </button>
                        <button id="next-lb-btn" class="flex text-[10px] md:text-xs text-emerald-600 hover:text-emerald-400 transition-colors uppercase font-bold tracking-widest items-center gap-1">
                            <span data-en="NEXT" data-ms="SETERUSNYA">NEXT</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                    @endif
                </div>

                @if(isset($leaderboard) && count($leaderboard) > 0)
                    <div class="flex flex-col gap-4">
                        @foreach($leaderboard as $index => $entry)
                            @php 
                                $entryRank = $index + 1;
                                $entryDetails = getRankDetails($entryRank);
                                $isCurrentUser = auth()->check() && auth()->id() == $entry->user_id;
                                $trueLeaderboardScore = $entry->level_1_score + $entry->level_2_score + $entry->level_3_score + $entry->level_4_score + $entry->level_5_score + $entry->level_6_score;
                                $page = $index < 5 ? 1 : 2;
                            @endphp
                            
                            <div class="lb-item theme-card border {{ $isCurrentUser ? 'border-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.2)]' : 'border-gray-700/50' }} rounded-lg p-4 md:p-5 {{ $page == 2 ? 'hidden' : 'flex' }} justify-between items-center transition-all hover:border-emerald-400" data-page="{{ $page }}">
                                <div class="flex items-center gap-3 md:gap-5 w-full">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-black shrink-0 border-2" style="background-color: {{ $entryDetails['color'] }}20; color: {{ $entryDetails['color'] }}; border-color: {{ $entryDetails['color'] }}; box-shadow: 0 0 10px {{ $entryDetails['color'] }}40;">
                                        {{ $entryRank }}
                                    </div>
                                    <div class="flex flex-col overflow-hidden w-full">
                                        <span class="theme-value text-lg md:text-xl font-extrabold truncate {{ $isCurrentUser ? 'text-emerald-400' : '' }}">
                                            {{ $entry->user->name ?? 'Unknown Agent' }} 
                                            @if($isCurrentUser) <span class="text-[10px] bg-emerald-900/50 text-emerald-400 px-2 py-0.5 rounded ml-2 uppercase align-middle">You</span> @endif
                                        </span>
                                        <span class="text-[10px] md:text-xs uppercase tracking-widest font-bold truncate opacity-90 transition-colors" style="color: {{ $entryDetails['color'] }}">{{ $entryDetails['title'] }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-emerald-500 text-xl md:text-2xl font-black shrink-0 ml-2">{{ $trueLeaderboardScore }}</span>
                                    <span class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">/ {{ $trueMaxScore }} PTS</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-sm mt-10 py-5 uppercase tracking-widest opacity-60 theme-value" data-en="Awaiting agent data..." data-ms="Menunggu data ejen...">Awaiting agent data...</div>
                @endif
            </div>

            <div class="flex flex-col gap-6">
                <button id="nav-mission" class="w-full bg-emerald-500 hover:bg-emerald-400 text-black font-bold py-5 px-4 rounded-lg flex items-center justify-center gap-3 uppercase tracking-widest border border-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.6)] hover:shadow-[0_0_30px_rgba(16,185,129,0.8)] transition-all transform hover:-translate-y-1 sc-anim sc-in-right d-2">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                    <span data-en="{{ isset($roomsCompleted) && $roomsCompleted == 5 ? 'Replay Missions' : 'Start Mission' }}" data-ms="{{ isset($roomsCompleted) && $roomsCompleted == 5 ? 'Main Semula' : 'Mula Misi' }}">{{ isset($roomsCompleted) && $roomsCompleted == 5 ? 'Replay Missions' : 'Start Mission' }}</span>
                </button>

                <button data-url="{{ route('profile.edit') }}" class="w-full theme-card backdrop-blur-md text-purple-500 font-bold py-4 px-4 rounded-lg flex items-center justify-center gap-3 uppercase tracking-widest text-sm border border-purple-500/60 sc-anim sc-in-right d-3 hover:bg-purple-500/10 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span data-en="My Profile" data-ms="Profil Saya">My Profile</span>
                </button>

                <button data-url="{{ route('agent.gallery') }}" class="w-full theme-card backdrop-blur-md text-emerald-400 font-bold py-4 px-4 rounded-lg flex items-center justify-center gap-3 uppercase tracking-widest text-sm border border-emerald-500/60 sc-anim sc-in-right d-3 hover:bg-emerald-500/10 transition-colors shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span data-en="Info Gallery" data-ms="Galeri Info">Info Gallery</span>
                </button>

                <button id="director-access-btn" class="w-full theme-card backdrop-blur-md text-blue-400 font-bold py-4 px-4 rounded-lg flex items-center justify-center gap-3 uppercase tracking-widest text-sm border border-blue-500/60 sc-anim sc-in-right d-4 hover:bg-blue-500/10 transition-colors shadow-[0_0_15px_rgba(59,130,246,0.1)]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <span data-en="Admin Access" data-ms="Akses Admin">Director Access</span>
                </button>

                <form method="POST" action="{{ route('logout') }}" class="w-full sc-anim sc-in-right d-5" id="logout-form">
                    @csrf
                    <button type="submit" class="w-full theme-card backdrop-blur-md text-red-500 font-bold py-4 px-4 rounded-lg flex items-center justify-center gap-3 uppercase tracking-widest text-sm border border-red-500/60 shadow-[0_0_15px_rgba(239,68,68,0.3)] hover:shadow-[0_0_25px_rgba(239,68,68,0.5)] transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span data-en="Logout" data-ms="Log Keluar">Logout</span>
                    </button>
                </form>

                <div class="theme-card backdrop-blur-md rounded-lg p-5 font-mono text-xs border border-emerald-500/30 relative overflow-hidden mt-2 sc-anim sc-in-right d-6">
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    <div class="relative z-10 flex flex-col gap-3">
                        <div class="flex justify-between items-center border-b border-gray-500/30 pb-2">
                            <span class="theme-value opacity-70" data-en="> TERMINAL STATUS:" data-ms="> STATUS TERMINAL:">> TERMINAL STATUS:</span>
                            <span class="text-emerald-500 font-bold drop-shadow-[0_0_5px_rgba(16,185,129,0.8)]" data-en="ONLINE" data-ms="AKTIF">ONLINE</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-500/30 pb-2">
                            <span class="theme-value opacity-70" data-en="> CLEARANCE:" data-ms="> AKSES:">> CLEARANCE:</span>
                            <span class="text-purple-500 font-bold" data-en="AGENT" data-ms="EJEN">AGENT</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="theme-value opacity-70" data-en="> MISSION STATUS:" data-ms="> STATUS MISI:">> MISSION STATUS:</span>
                            @if(isset($roomsCompleted) && $roomsCompleted == 5)
                                <span class="text-emerald-500 font-bold" data-en="SECURED" data-ms="SELESAI">SECURED</span>
                            @elseif(isset($roomsCompleted) && $roomsCompleted > 0)
                                <span class="text-blue-500 font-bold" data-en="ACTIVE" data-ms="AKTIF">ACTIVE</span>
                            @else
                                <span class="text-yellow-500 font-bold" data-en="STANDBY" data-ms="SEDIA">STANDBY</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div id="director-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 transition-opacity duration-300 opacity-0" style="background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px);">
        <div class="theme-card w-full max-w-md p-8 relative border border-blue-500/50 shadow-[0_0_30px_rgba(59,130,246,0.3)] rounded-lg transform scale-95 transition-transform duration-300" id="director-modal-content">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-blue-900/50 border border-blue-500 flex items-center justify-center text-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.6)]">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
            </div>
            
            <h3 class="text-blue-400 font-bold uppercase tracking-widest mb-6 text-center text-lg" data-en="Admin Authorization Required" data-ms="Pengesahan Admin Diperlukan">Admin Authorization Required</h3>
            <input type="password" id="director-password" class="lz-input mb-4" placeholder="•••••••••">
            <p id="director-error" class="text-red-500 text-xs font-bold hidden mb-4 text-center uppercase tracking-widest animate-pulse" data-en="ACCESS DENIED: INVALID CREDENTIALS" data-ms="AKSES DITOLAK: KREDENSIAL TIDAK SAH">ACCESS DENIED: INVALID CREDENTIALS</p>
            
            <div class="flex gap-3 mt-6">
                <button id="verify-director" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded uppercase tracking-widest text-xs transition-colors shadow-[0_0_15px_rgba(37,99,235,0.4)]">
                    <span data-en="Verify Identity" data-ms="Sahkan Identiti">Verify Identity</span>
                </button>
                <button onclick="closeDirectorModal()" class="flex-1 bg-transparent border border-gray-600 text-red-400 hover:text-black hover:border-grey-400 py-3 rounded uppercase tracking-widest text-xs transition-colors">
                    <span data-en="Abort" data-ms="Batal">Abort</span>
                </button>
            </div>
        </div>
    </div>

    <div id="score-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 transition-opacity duration-300 opacity-0" style="background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px);">
        <div class="theme-card w-full max-w-md p-6 md:p-8 relative border border-emerald-500/50 shadow-[0_0_30px_rgba(16,185,129,0.3)] rounded-lg transform scale-95 transition-all duration-300 flex flex-col" id="score-modal-content">
            <button onclick="toggleMaximizeModal()" title="Toggle Fullscreen" class="absolute top-4 right-4 md:top-6 md:right-6 text-emerald-500/70 hover:text-emerald-400 transition-colors p-2 rounded-lg bg-black/20 hover:bg-black/40 z-50 border border-emerald-500/30">
                <svg id="maximize-icon" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
            </button>

            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-emerald-900/50 border border-emerald-500 flex items-center justify-center text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.6)]">
                    <svg class="h-6 w-6 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
            </div>
            <h3 class="text-emerald-400 font-bold uppercase tracking-widest mb-4 text-center text-base md:text-lg" data-en="Score Diagnostics" data-ms="Pecahan Markah">Score Diagnostics</h3>

            <div class="tab-buttons-container flex bg-black/50 p-1 rounded border border-gray-500/50 mb-6 gap-1 shrink-0">
                <button id="tab-btn-core" onclick="switchScoreTab('core')" class="flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded bg-emerald-600 text-black transition-all shadow-[0_0_10px_rgba(16,185,129,0.5)]">Core</button>
                <button id="tab-btn-branching" onclick="switchScoreTab('branching')" class="flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded theme-value opacity-60 hover:opacity-100 transition-all">Branching</button>
                <button id="tab-btn-arcade" onclick="switchScoreTab('arcade')" class="flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded theme-value opacity-60 hover:opacity-100 transition-all">Arcade</button>
            </div>
            
            <div id="score-page-core" style="display: flex;" class="tab-content-wrapper flex-col h-full space-y-3 font-mono text-xs md:text-sm mb-6 relative">
                <div class="modal-body-scroll overflow-y-auto max-h-[140px] pr-2 custom-scrollbar transition-all duration-300">
                    <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                        <span class="theme-value opacity-80">MOD 01: Phishing Net</span>
                        <span class="text-emerald-500 font-bold shrink-0">{{ $progress->level_1_score ?? 0 }} PTS</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                        <span class="theme-value opacity-80">MOD 02: Brute Force Gate</span>
                        <span class="text-emerald-500 font-bold shrink-0">{{ $progress->level_2_score ?? 0 }} PTS</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                        <span class="theme-value opacity-80">MOD 03: Human Firewall</span>
                        <span class="text-emerald-500 font-bold shrink-0">{{ $progress->level_3_score ?? 0 }} PTS</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                        <span class="theme-value opacity-80">MOD 04: Mirroring Web</span>
                        <span class="text-emerald-500 font-bold shrink-0">{{ $progress->level_4_score ?? 0 }} PTS</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                        <span class="text-red-500 font-bold">MOD 05: S.H.I.E.L.D Boss</span>
                        <span class="text-emerald-500 font-bold shrink-0">{{ $progress->level_5_score ?? 0 }} PTS</span>
                    </div>
                </div>
                <div class="total-row flex justify-between pt-3 border-t border-gray-500/50 text-sm md:text-base mt-auto shrink-0">
                    <span class="theme-value font-bold uppercase">Core Total</span>
                    <span class="text-emerald-500 font-bold">{{ $coreScore }} PTS</span>
                </div>
            </div>

            <div id="score-page-branching" style="display: none;" class="tab-content-wrapper flex-col h-full space-y-3 font-mono text-xs md:text-sm mb-6 relative">
                <div class="flex justify-between items-center mb-2 border-b border-gray-500/30 pb-2 shrink-0">
                    <span class="text-[10px] md:text-xs theme-value opacity-60 italic" data-en="Custom Scenario Results" data-ms="Keputusan Senario Khas">Custom Scenario Results</span>
                </div>

                <div class="modal-body-scroll overflow-y-auto max-h-[140px] pr-2 custom-scrollbar transition-all duration-300">
                    @forelse($customRooms as $cRoom)
                        <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                            <span class="theme-value opacity-80 truncate pr-2" title="{{ $cRoom->title }}">{{ $cRoom->title }}</span>
                            <span class="font-bold shrink-0" style="color: var(--score-branching-text)">{{ $customScoresArray[$cRoom->id] ?? 0 }} PTS</span>
                        </div>
                    @empty
                        <div class="text-center theme-value opacity-50 italic py-6">No custom modules active yet.</div>
                    @endforelse
                </div>

                <div class="total-row flex justify-between pt-3 border-t border-gray-500/50 text-sm md:text-base mt-auto shrink-0">
                    <span class="theme-value font-bold uppercase">Branching Total</span>
                    <span class="font-bold" style="color: var(--score-branching-text)">{{ $branchingScore }} PTS</span>
                </div>
            </div>

            <div id="score-page-arcade" style="display: none;" class="tab-content-wrapper flex-col h-full space-y-3 font-mono text-xs md:text-sm mb-6 relative">
                <div class="modal-body-scroll overflow-y-auto max-h-[140px] pr-2 custom-scrollbar transition-all duration-300">
                    @forelse($completedArcadeGames as $arcadeGame)
                        <div class="flex justify-between border-b border-gray-500/30 pb-2 mb-2">
                            <span class="theme-value opacity-80 truncate pr-2">{{ $arcadeGame->title }}</span>
                            <span class="text-purple-500 font-bold shrink-0">{{ $arcadeGame->base_score }} PTS</span>
                        </div>
                    @empty
                        <div class="text-center theme-value opacity-50 italic py-6">No arcade modules completed yet.</div>
                    @endforelse
                </div>

                <p class="text-[10px] md:text-xs theme-value opacity-60 italic mt-2 shrink-0">Note: Total arcade points contribute to your overall Global Leaderboard ranking.</p>
                
                <div class="total-row flex justify-between pt-3 border-t border-gray-500/50 text-sm md:text-base mt-auto shrink-0">
                    <span class="theme-value font-bold uppercase">Arcade Total</span>
                    <span class="text-purple-500 font-bold">{{ $arcadeScore }} PTS</span>
                </div>
            </div>
            
            <button onclick="closeScoreModal()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-black font-bold py-3 rounded uppercase tracking-widest text-xs transition-colors shadow-[0_0_15px_rgba(16,185,129,0.4)] shrink-0">
                <span data-en="Close Diagnostics" data-ms="Tutup Analisis">Close Diagnostics</span>
            </button>
        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const clickSound = document.getElementById('ui-click-sound');
            if(clickSound) {
                clickSound.volume = 0.6; 
                
                document.querySelectorAll('button, a, .cursor-pointer').forEach(element => {
                    element.addEventListener('click', function(e) {
                        clickSound.currentTime = 0; 
                        clickSound.play().catch(err => console.log("Click sound blocked:", err));

                        if (this.hasAttribute('data-url')) {
                            e.preventDefault();
                            let target = this.getAttribute('data-url');
                            setTimeout(() => { window.location.href = target; }, 250);
                        }

                        if (this.type === 'submit' && this.closest('form')) {
                            e.preventDefault();
                            let form = this.closest('form');
                            setTimeout(() => { form.submit(); }, 250);
                        }
                    });
                });
            }

            const nextLbBtn = document.getElementById('next-lb-btn');
            const prevLbBtn = document.getElementById('prev-lb-btn');
            const lbItems = document.querySelectorAll('.lb-item');

            if (nextLbBtn && prevLbBtn) {
                nextLbBtn.addEventListener('click', () => {
                    lbItems.forEach(item => {
                        if (item.getAttribute('data-page') === '2') {
                            item.classList.remove('hidden'); item.classList.add('flex');
                        } else {
                            item.classList.remove('flex'); item.classList.add('hidden');
                        }
                    });
                    nextLbBtn.classList.add('hidden'); nextLbBtn.classList.remove('flex');
                    prevLbBtn.classList.remove('hidden'); prevLbBtn.classList.add('flex');
                });

                prevLbBtn.addEventListener('click', () => {
                    lbItems.forEach(item => {
                        if (item.getAttribute('data-page') === '1') {
                            item.classList.remove('hidden'); item.classList.add('flex');
                        } else {
                            item.classList.remove('flex'); item.classList.add('hidden');
                        }
                    });
                    prevLbBtn.classList.add('hidden'); prevLbBtn.classList.remove('flex');
                    nextLbBtn.classList.remove('hidden'); nextLbBtn.classList.add('flex');
                });
            }
        });
    </script>

    <script>
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

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
                if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'H3' || el.tagName === 'DIV' || el.tagName === 'BUTTON') {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan) textSpan.innerHTML = el.getAttribute(`data-${lang}`);
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
                    bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE';
                } else {
                    bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE';
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

        document.getElementById('nav-mission').addEventListener('click', function(e) {
            e.preventDefault(); 
            const elements = document.querySelectorAll('.sc-anim');
            elements.forEach(el => {
                if (el.classList.contains('sc-in-left')) {
                    el.classList.remove('sc-in-left'); el.classList.add('sc-out-left');
                } else if (el.classList.contains('sc-in-right')) {
                    el.classList.remove('sc-in-right'); el.classList.add('sc-out-right');
                }
            });
            setTimeout(() => { window.location.href = "{{ route('agent.mission') }}"; }, 850);
        });

        const directorBtn = document.getElementById('director-access-btn');
        const directorModal = document.getElementById('director-modal');
        const modalContent = document.getElementById('director-modal-content');
        const verifyBtn = document.getElementById('verify-director');
        const passInput = document.getElementById('director-password');
        const errorMsg = document.getElementById('director-error');

        directorBtn.addEventListener('click', () => {
            directorModal.classList.remove('hidden'); directorModal.classList.add('flex');
            setTimeout(() => {
                directorModal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95'); modalContent.classList.add('scale-100');
            }, 10);
            passInput.value = ''; errorMsg.classList.add('hidden'); passInput.focus();
        });

        function closeDirectorModal() {
            directorModal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100'); modalContent.classList.add('scale-95');
            setTimeout(() => {
                directorModal.classList.add('hidden'); directorModal.classList.remove('flex');
            }, 300); 
        }

        verifyBtn.addEventListener('click', () => {
            if(passInput.value === 'C@kn4$!83R') {
                closeDirectorModal();
                const elements = document.querySelectorAll('.sc-anim');
                elements.forEach(el => {
                    if (el.classList.contains('sc-in-left')) {
                        el.classList.remove('sc-in-left'); el.classList.add('sc-out-left');
                    } else if (el.classList.contains('sc-in-right')) {
                        el.classList.remove('sc-in-right'); el.classList.add('sc-out-right');
                    }
                });
                setTimeout(() => { window.location.href = "/admin"; }, 850);
            } else {
                errorMsg.classList.remove('hidden'); passInput.value = ''; passInput.focus();
                modalContent.classList.add('animate-[shake_0.5s_ease-in-out]');
                setTimeout(() => { modalContent.classList.remove('animate-[shake_0.5s_ease-in-out]'); }, 500);
            }
        });

        passInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { verifyBtn.click(); }
        });

        const scoreModal = document.getElementById('score-modal');
        const scoreModalContent = document.getElementById('score-modal-content');
        const tabCore = document.getElementById('tab-btn-core');
        const tabBranching = document.getElementById('tab-btn-branching');
        const tabArcade = document.getElementById('tab-btn-arcade');
        const pageCore = document.getElementById('score-page-core');
        const pageBranching = document.getElementById('score-page-branching');
        const pageArcade = document.getElementById('score-page-arcade');

        let isMaximized = false;
        const maxIcon = document.getElementById('maximize-icon');

        function openScoreModal() {
            scoreModal.classList.remove('hidden'); scoreModal.classList.add('flex');
            switchScoreTab('core');
            setTimeout(() => {
                scoreModal.classList.remove('opacity-0');
                scoreModalContent.classList.remove('scale-95'); scoreModalContent.classList.add('scale-100');
            }, 10);
        }

        function closeScoreModal() {
            scoreModal.classList.add('opacity-0');
            scoreModalContent.classList.remove('scale-100'); scoreModalContent.classList.add('scale-95');
            if(isMaximized) toggleMaximizeModal();
            setTimeout(() => {
                scoreModal.classList.add('hidden'); scoreModal.classList.remove('flex');
            }, 300); 
        }

        function switchScoreTab(tab) {
            const defaultClass = "flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded theme-value opacity-60 hover:opacity-100 transition-all";
            tabCore.className = defaultClass; tabBranching.className = defaultClass; tabArcade.className = defaultClass;
            tabBranching.style.backgroundColor = ""; tabBranching.style.color = "";
            pageCore.style.display = 'none'; pageBranching.style.display = 'none'; pageArcade.style.display = 'none';

            if (tab === 'core') {
                tabCore.className = "flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded bg-emerald-600 text-black transition-all shadow-[0_0_10px_rgba(16,185,129,0.5)]";
                pageCore.style.display = 'flex';
            } else if (tab === 'branching') {
                tabBranching.className = "flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded transition-all shadow-[0_0_10px_var(--tab-branching-bg)]";
                tabBranching.style.backgroundColor = "var(--tab-branching-bg)"; tabBranching.style.color = "var(--tab-branching-text)";
                pageBranching.style.display = 'flex';
            } else {
                tabArcade.className = "flex-1 py-2 text-[10px] md:text-[11px] font-bold tracking-widest uppercase rounded bg-purple-600 text-white transition-all shadow-[0_0_10px_rgba(147,51,234,0.5)]";
                pageArcade.style.display = 'flex';
            }
        }

        function toggleMaximizeModal() {
            isMaximized = !isMaximized;
            if (isMaximized) {
                scoreModalContent.classList.add('modal-maximized');
                maxIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h6m0 0v6m0-6l-7 7m17-11h-6m0 0V4m0 6l-7-7m17 11h-6m0 0v6m0-6l7 7" />`;
            } else {
                scoreModalContent.classList.remove('modal-maximized');
                maxIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />`;
            }
        }
    </script>

    <script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => { window.parent.postMessage('ensureMusicPlaying', '*'); }, 100);
    });
    </script>
    @include('partials.cursor')
</body>
</html>