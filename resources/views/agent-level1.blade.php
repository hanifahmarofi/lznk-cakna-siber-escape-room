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
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // The Phishing Net</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;600;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        html,
    body,
    body * {
    cursor: none !important;
}
        body { 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            font-family: 'Share Tech Mono', monospace; 
            overflow-x: hidden; 
            overflow-y: auto;
            transition: background-color 0.3s ease; 
        }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        .ui-font { font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; } /* Realistic UI Font */
        
        :root {
            /* General App Variables (Keep mostly intact for briefing/end screens) */
            --bg-color: #050505; 
            --text-color: #d1d5db; 
            --terminal-bg: rgba(10, 15, 20, 0.95);
            --terminal-border: #10b981;
            --terminal-header: #064e3b;
            --terminal-shadow: 0 0 30px rgba(16, 185, 129, 0.15);
            
            --btn-bg: rgba(16, 185, 129, 0.1);
            --btn-border: rgba(16, 185, 129, 0.5);
            --btn-hover-bg: rgba(16, 185, 129, 0.2);
            --btn-hover-border: #34d399;
            --btn-text: #6ee7b7;
            
            --toggle-bg: rgba(16, 185, 129, 0.1);
            --toggle-text: #10b981;
            --toggle-hover-bg: rgba(16, 185, 129, 0.2);
            --toggle-border: rgba(16, 185, 129, 0.5);

            --card-bg: rgba(21, 21, 21, 0.85); 
            --card-hover: rgba(31, 31, 31, 0.9);
            --title-color: #10b981;
            --value-color: #ffffff;

            /* Outlook Realistic Variables (Dark Mode Default) */
            --ol-bg: #1e1e1e;
            --ol-sidebar: #252526;
            --ol-list: #1e1e1e;
            --ol-reading: #1e1e1e;
            --ol-border: #3e3e42;
            --ol-text-primary: #cccccc;
            --ol-text-secondary: #858585;
            --ol-accent: #0078d4;
            --ol-hover: #2a2d2e;
            --ol-selected: #37373d;
            --ol-header-bg: #111111;

            /* 🔥 VIDEO BACKGROUND VARIABLES (DARK MODE EMERALD) 🔥 */
            --vid-filter: none;
            --vid-overlay: rgba(6, 78, 59, 0.25);
        }
        
        .light-mode {
            /* General App Variables */
            --bg-color: #f8fafc; 
            --text-color: #0f172a;
            --terminal-bg: rgba(255, 255, 255, 0.95);
            --terminal-border: #059669; 
            --terminal-header: #6ee7b7; 
            --terminal-shadow: 0 10px 25px rgba(5, 150, 105, 0.2);
            
            --btn-bg: rgba(239, 253, 244, 0.8); 
            --btn-border: #34d399; 
            --btn-hover-bg: #d1fae5; 
            --btn-hover-border: #10b981; 
            --btn-text: #064e3b; 
            
            --toggle-bg: rgba(255, 255, 255, 0.9);
            --toggle-text: #0f172a;
            --toggle-hover-bg: #e2e8f0;
            --toggle-border: #94a3b8;

            --card-bg: rgba(255, 255, 255, 0.95); 
            --card-hover: rgba(243, 244, 246, 0.98);
            --title-color: #047857;
            --value-color: #0f172a;

            /* Outlook Realistic Variables (Light Mode) */
            --ol-bg: #f3f2f1;
            --ol-sidebar: #f3f2f1;
            --ol-list: #ffffff;
            --ol-reading: #ffffff;
            --ol-border: #edebe9;
            --ol-text-primary: #323130;
            --ol-text-secondary: #605e5c;
            --ol-accent: #0078d4;
            --ol-hover: #f3f2f1;
            --ol-selected: #edebe9;
            --ol-header-bg: #0078d4;

            /* 🔥 VIDEO BACKGROUND VARIABLES (LIGHT MODE GREEN INVERT) 🔥 */
            --vid-filter: invert(1) sepia(1) hue-rotate(90deg) saturate(200%) brightness(1.1);
            --vid-overlay: rgba(255, 255, 255, 0.5);
        }
        
        /* 🔥 THEME VIDEO CLASSES 🔥 */
        .theme-video { filter: var(--vid-filter); transition: filter 0.5s ease; }
        .theme-vid-overlay { background-color: var(--vid-overlay); transition: background-color 0.5s ease; }

        .light-mode .text-gray-300, .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #000000 !important; }
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-emerald-500 { color: #047857 !important; }
        .light-mode .text-emerald-400 { color: #059669 !important; } 
        .light-mode .text-emerald-300 { color: #065f46 !important; }
        .light-mode .drop-shadow-\[0_0_10px_rgba\(16\,185\,129\,0\.8\)\] { filter: drop-shadow(0 0 5px rgba(5, 150, 105, 0.4)); }
        .light-mode .bg-black\/40 { background-color: rgba(255, 255, 255, 0.9) !important; }
        .light-mode .border-blue-900\/50 { border-color: rgba(0, 0, 0, 0.15) !important; }

        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; pointer-events: none; }
        
        .game-container { max-width: 1200px; margin: 0 auto; min-height: 100vh; display: flex; flex-direction: column; justify-content: center; padding-top: 5rem; padding-bottom: 2rem; }
        
        .terminal-box { background: var(--terminal-bg); border: 1px solid var(--terminal-border); box-shadow: var(--terminal-shadow); border-radius: 8px; overflow: hidden; position: relative; transition: all 0.3s ease;}
        
        /* Briefing/End Screen specific headers */
        .classic-header { background: var(--terminal-header); padding: 16px 24px; border-bottom: 1px solid var(--terminal-border); display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease;}
        
        .theme-btn { background: var(--toggle-bg); color: var(--toggle-text); border: 1px solid var(--toggle-border); transition: all 0.3s ease; }
        .theme-btn:hover { background: var(--toggle-hover-bg); }
        
        /* For tutorial modal */
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; }
        .theme-title { color: var(--title-color) !important; transition: color 0.3s ease; }
        .theme-value { color: var(--value-color) !important; transition: color 0.3s ease; }

        .sc-anim { opacity: 0; transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .sc-in-left { transform: translateX(-100vw); }
        .sc-in-right { transform: translateX(100vw); }
        body.loaded .sc-anim { opacity: 1; transform: translate(0, 0); }

        .tv-turn-on { animation: tvOn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes tvOn {
            0% { transform: scale(0.01, 0.001); opacity: 0; filter: brightness(10) contrast(10); }
            50% { transform: scale(1, 0.001); opacity: 1; filter: brightness(5) contrast(5); }
            100% { transform: scale(1, 1); opacity: 1; filter: brightness(1) contrast(1); }
        }

        /* Epic Countdown Styles */
        .epic-bg { background: radial-gradient(circle at center, #6b21a8 0%, #1e1b4b 60%, #000000 100%); }
        .epic-text {
            font-family: 'Anton', sans-serif;
            background: linear-gradient(180deg, #ecfdf5 0%, #10b981 50%, #064e3b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0px 0px 15px rgba(16, 185, 129, 0.8));
        }

        .epic-count { animation: epicZoom 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes epicZoom {
            0% { transform: scale(1.8); opacity: 0; filter: brightness(2) drop-shadow(0 0 50px rgba(255,255,255,1)); }
            20% { opacity: 1; filter: brightness(1.5) drop-shadow(0 0 30px rgba(16, 185, 129, 0.8)); }
            100% { transform: scale(1); opacity: 1; filter: brightness(1) drop-shadow(0 0 10px rgba(16, 185, 129, 0.5)); }
        }

        .epic-flare { animation: flarePulse 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes flarePulse {
            0% { transform: scaleY(0) scaleX(0); opacity: 0; }
            10% { transform: scaleY(3) scaleX(1); opacity: 1; }
            100% { transform: scaleY(0) scaleX(3); opacity: 0; }
        }

        .particles {
            position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(circle, #fff 1px, transparent 1px);
            background-size: 60px 60px;
            opacity: 0.15;
            animation: moveParticles 15s linear infinite;
        }
        @keyframes moveParticles {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-100px) scale(1.2); }
        }

        .white-flash { position: fixed; inset: 0; background: white; z-index: 9999; animation: flashBang 0.8s forwards; pointer-events: none; }
        @keyframes flashBang { 0% { opacity: 1; } 100% { opacity: 0; } }

        .shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; border-color: #ef4444 !important; box-shadow: 0 0 30px rgba(239, 68, 68, 0.4) !important; }
        .glitch-text { color: #ef4444; text-shadow: 2px 0 blue, -2px 0 lime; }
        
        @keyframes shake {
            10%, 90% { transform: translate3d(-2px, 0, 0); }
            20%, 80% { transform: translate3d(4px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-8px, 0, 0); }
            40%, 60% { transform: translate3d(8px, 0, 0); }
        }

        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* --- OUTLOOK SPECIFIC STYLES --- */
        .outlook-app {
            display: flex;
            flex-direction: column;
            height: 100%;
            background-color: var(--ol-bg);
            color: var(--ol-text-primary);
        }
        
        .outlook-header {
            background-color: var(--ol-header-bg);
            color: white;
            height: 48px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .outlook-toolbar {
            height: 44px;
            border-bottom: 1px solid var(--ol-border);
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 16px;
            font-size: 14px;
        }

        .outlook-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar Pane */
        .outlook-sidebar {
            width: 220px;
            background-color: var(--ol-sidebar);
            border-right: 1px solid var(--ol-border);
            display: flex;
            flex-direction: column;
            padding-top: 12px;
        }
        .ol-nav-item {
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            cursor: default;
        }
        .ol-nav-item:hover { background-color: var(--ol-hover); }
        .ol-nav-item.active { background-color: var(--ol-selected); border-left: 3px solid var(--ol-accent); padding-left: 13px; font-weight: 600; }
        .ol-nav-icon { width: 16px; height: 16px; opacity: 0.8; }

        /* List Pane */
        .outlook-list {
            width: 300px;
            background-color: var(--ol-list);
            border-right: 1px solid var(--ol-border);
            display: flex;
            flex-direction: column;
        }
        .ol-list-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--ol-border);
            font-weight: 600;
            font-size: 16px;
        }
        .ol-list-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--ol-border);
            cursor: pointer;
        }
        .ol-list-item.active {
            background-color: var(--ol-selected);
            border-left: 3px solid var(--ol-accent);
            padding-left: 13px;
        }
        .ol-list-sender { font-weight: 600; font-size: 14px; margin-bottom: 2px; }
        .ol-list-subject { font-size: 13px; color: var(--ol-accent); margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ol-list-preview { font-size: 12px; color: var(--ol-text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Reading Pane */
        .outlook-reading {
            flex: 1;
            background-color: var(--ol-reading);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .ol-reading-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--ol-border);
        }
        .ol-reading-subject {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        .ol-meta-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .ol-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--ol-accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }
        .ol-sender-details { flex: 1; }
        .ol-sender-name { font-weight: 600; font-size: 14px; }
        .ol-sender-email { font-size: 12px; color: var(--ol-text-secondary); }
        .ol-date { font-size: 12px; color: var(--ol-text-secondary); }

        .ol-body {
            padding: 24px;
            font-size: 14px;
            line-height: 1.6;
            overflow-y: auto;
            flex: 1;
            white-space: pre-wrap;
        }

        /* Action Buttons Area */
        .ol-actions {
            padding: 16px 24px;
            border-top: 1px solid var(--ol-border);
            background-color: var(--ol-reading);
            display: flex;
            gap: 12px;
        }
        .ol-btn {
            flex: 1;
            padding: 12px;
            border-radius: 4px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            pointer-events: auto;
        }
        .ol-btn-safe {
            background-color: transparent;
            border-color: #10b981;
            color: #10b981;
        }
        .ol-btn-safe:hover { background-color: rgba(16, 185, 129, 0.1); }
        .ol-btn-danger {
            background-color: #dc2626;
            color: white;
        }
        .ol-btn-danger:hover { background-color: #b91c1c; }

        /* Security Banner */
        .ol-sec-banner {
            background-color: rgba(234, 179, 8, 0.1);
            border-bottom: 1px solid rgba(234, 179, 8, 0.3);
            padding: 8px 24px;
            font-size: 12px;
            color: #d97706;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .light-mode .ol-sec-banner { background-color: #fef9c3; color: #b45309; }

        /* Mobile adjustments for Outlook UI */
        @media (max-width: 768px) {
            .outlook-sidebar { display: none; }
            .outlook-list { display: none; }
        }

        /* 🤖 GOOGLE TRANSLATE OVERRIDES */
        iframe.skiptranslate { display: none !important; } 
        .goog-te-banner-frame { display: none !important; }
        .goog-te-menu-value { display: none !important; }
        .VIpgJd-Zvi9od-ORHb-OEVmcd { display: none !important; } 
        body { top: 0px !important; position: relative; }
        #google_translate_element { display: none !important; pointer-events: none !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
    </style>
</head>
<body class="relative">

    <div id="google_translate_element" class="hidden"></div>

    <audio id="room-music" src="{{ asset('audio/Protocol_for_a_Cold_Planet.mp3') }}" loop preload="auto" crossorigin="anonymous" class="hidden"></audio>
    <audio id="epic-audio" preload="auto" class="hidden"><source src="{{ asset('audio/countdown-epic.mp3') }}" type="audio/mpeg"></audio>
    <audio id="correct-audio" preload="auto" class="hidden"><source src="{{ asset('audio/right-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="wrong-audio" preload="auto" class="hidden"><source src="{{ asset('audio/wrong-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto" class="hidden"></audio>

    <div class="fixed top-4 right-4 z-[9999999] flex gap-3 sc-anim sc-in-right d-5 pointer-events-auto">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-black transition-colors text-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.2)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-black transition-colors text-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.2)]">
            ☀️ LIGHT MODE
        </button>

         <a href="{{ route('agent.mission') }}" class="px-5 py-2 theme-card border border-purple-500/50 theme-title rounded hover:bg-purple-600 hover:text-white transition-all text-[10px] md:text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(147,51,234,0.3)] flex items-center justify-center gap-2">
                <span data-en="ABORT" data-ms="BATAL">ABORT</span>
            </a>
    </div>

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-30 theme-video">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 theme-vid-overlay mix-blend-color"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-transparent to-black/95 light-mode:hidden"></div>
        <div class="absolute inset-0 scanlines opacity-40"></div>
    </div>

    <div id="flash-bang-container" class="fixed inset-0 z-[99995] pointer-events-none"></div>

    <div id="countdown-screen" class="hidden fixed inset-0 z-[99990] flex items-center justify-center epic-bg overflow-hidden pointer-events-none">
        <div class="particles"></div>
        <div id="lens-flare" class="absolute w-[200%] h-2 bg-white shadow-[0_0_60px_20px_#e879f9] opacity-0 -rotate-12 z-20 mix-blend-screen pointer-events-none"></div>
        <div id="countdown-number" class="relative z-10 text-[18rem] md:text-[25rem] epic-text pointer-events-none opacity-0">3</div>
    </div>

    <div id="tutorial-modal" class="hidden fixed inset-0 z-[99900] flex-col items-center justify-center p-4 transition-opacity duration-500 opacity-0 bg-black/80 backdrop-blur-md">
        <div class="theme-card border border-emerald-500/50 max-w-4xl w-full max-h-[95vh] overflow-y-auto p-6 md:p-8 rounded-xl shadow-[0_0_40px_rgba(16,185,129,0.3)] flex flex-col pointer-events-auto">
            
            <h2 class="theme-title text-2xl font-bold uppercase tracking-widest mb-4 flex items-center gap-3 shrink-0">
                <svg class="w-6 h-6 animate-pulse text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="translation-target" data-en="Pre-Mission Guidance" data-ms="Panduan Pra-Misi">Panduan Pra-Misi</span>
            </h2>
            
            <div class="relative w-full bg-black border border-gray-700 rounded-lg mb-6 overflow-hidden flex justify-center shadow-inner shrink-0" style="padding-bottom: 56.25%;">
                <video id="tutorial-video" controls class="absolute top-0 left-0 w-full h-full object-contain">
                    <source src="{{ asset('video/tutorial.mp4') }}" type="video/mp4">
                </video>
            </div>
            
            <div class="bg-emerald-500/10 border-l-4 border-emerald-500 p-4 mb-6 rounded text-left shrink-0">
                <p class="theme-value font-mono text-sm leading-relaxed"    
                 data-ms="Sila teliti video panduan di atas. Beri perhatian penuh kepada alamat penghantar, nada kecemasan, dan pautan yang mencurigakan. Setelah bersedia, tutup tetingkap ini untuk memasuki Peti Masuk Selamat."
                 data-en="Please study the guidance video above. Pay close attention to the sender's address, tone of urgency, and suspicious links. Once ready, close this window to enter the Secure Inbox.">
                 Sila teliti video panduan di atas. Beri perhatian penuh kepada alamat penghantar, nada kecemasan, dan pautan yang mencurigakan. Setelah bersedia, tutup tetingkap ini untuk memasuki Peti Masuk Selamat.
                </p>
            </div>
            
            <button onclick="closeTutorialAndStart()" class="relative z-50 w-full bg-emerald-600 hover:bg-emerald-500 text-black font-bold py-3 rounded uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.4)] transition-all pointer-events-auto shrink-0">
                <span class="translation-target" data-en="Close & Begin Analysis" data-ms="Tutup & Mulakan Analisis">Tutup & Mulakan Analisis</span>
            </button>
        </div>
    </div>

    <div class="game-container relative z-50 p-4" id="main-game-wrapper">
        
        <div class="flex justify-between items-end mb-4 px-2 sc-anim sc-in-left">
            <div>
                <h1 class="title-font text-3xl text-emerald-500 tracking-widest uppercase mb-1 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]">S.H.I.E.L.D. SECURE INBOX</h1>
                <p class="text-emerald-700 font-bold uppercase tracking-widest text-xs" data-en="Mission 01: The Phishing Net" data-ms="Misi 01: Jaring Phishing">Mission 01: The Phishing Net</p>
            </div>
            <div class="flex items-center gap-3 bg-black/40 border border-emerald-900/50 px-4 py-2 rounded-lg backdrop-blur-sm self-start md:self-auto">
                <div class="text-gray-400 text-[10px] uppercase tracking-widest whitespace-nowrap" data-en="System Integrity (Mistakes Allowed: 3)" data-ms="Integriti Sistem (Kesilapan Dibenarkan: 3)">System Integrity (Mistakes Allowed: 3)</div>
                <div class="flex gap-1" id="lives-container">
                    <svg class="w-6 h-6 text-emerald-500 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-6 h-6 text-emerald-500 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-6 h-6 text-emerald-500 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
        </div>

        <div id="briefing-screen" class="terminal-box flex flex-col h-[650px] justify-center items-center p-8 text-center bg-black sc-anim sc-in-right tv-turn-on">
            <h2 class="title-font text-4xl mb-4 uppercase tracking-wider text-emerald-500 drop-shadow-[0_0_15px_rgba(16,185,129,0.8)]" data-en="MISSION BRIEFING" data-ms="TAKLIMAT MISI">MISSION BRIEFING</h2>
            
            <div class="max-w-2xl text-gray-300 space-y-6 mb-10 text-base md:text-lg font-bold leading-relaxed">
                <p data-en="Welcome to <span class='text-emerald-400 font-extrabold'>The Phishing Net</span>. Cyber attackers often use deceptive emails to steal credentials or deploy malware." data-ms="Selamat datang ke <span class='text-emerald-400 font-extrabold'>Jaring Phishing</span>. Penyerang siber sering menggunakan e-mel penipuan untuk mencuri kelayakan atau memasang perisian hasad.">
                    Welcome to <span class="text-emerald-400 font-extrabold">The Phishing Net</span>. Cyber attackers often use deceptive emails to steal credentials or deploy malware.
                </p>
                <p data-en="Your objective is to analyze incoming emails in your inbox. You must determine if the message is a legitimate communication or a malicious phishing attempt." data-ms="Objektif anda adalah untuk menganalisis e-mel yang masuk dalam peti masuk anda. Anda mesti menentukan sama ada mesej itu adalah komunikasi yang sah atau percubaan memancing data (phishing) yang berniat jahat.">
                    Your objective is to analyze incoming emails in your inbox. You must determine if the message is a legitimate communication or a malicious phishing attempt.
                </p>
                <p class="text-red-500 font-extrabold" data-en="WARNING: You are only permitted 3 mistakes before the system locks you out." data-ms="AMARAN: Anda hanya dibenarkan 3 kesilapan sebelum sistem mengunci anda.">
                    WARNING: You are only permitted 3 mistakes before the system locks you out.
                </p>
                <p class="italic text-emerald-600 uppercase tracking-widest mt-4 font-extrabold" data-en="Good luck, Agent." data-ms="Semoga berjaya, Ejen.">Good luck, Agent.</p>
            </div>

            <button id="launch-btn" class="relative z-50 bg-emerald-600 text-black px-10 py-4 rounded font-bold text-xl uppercase tracking-widest hover:bg-emerald-500 transition-colors shadow-[0_0_20px_rgba(16,185,129,0.6)] cursor-pointer pointer-events-auto">
                <span data-en="Launch Mission" data-ms="Mulakan Misi">Launch Mission</span>
            </button>
        </div>

        <div id="game-terminal" class="terminal-box h-[650px] hidden ui-font">
            <div class="outlook-app">
                <div class="outlook-header">
                    <svg viewBox="0 0 16 16" class="w-4 h-4 mr-3 fill-current"><path d="M1 2.5a.5.5 0 01.5-.5h13a.5.5 0 01.5.5v11a.5.5 0 01-.5.5h-13a.5.5 0 01-.5-.5v-11zm1 1v9h12v-9H2z"></path></svg>
                    S.H.I.E.L.D. Secure Mail
                </div>
                
                <div class="outlook-toolbar">
                    <span class="flex items-center gap-2 cursor-default opacity-50"><svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M8 1a3.5 3.5 0 00-3.5 3.5v1A1.5 1.5 0 003 7v7a1.5 1.5 0 001.5 1.5h7A1.5 1.5 0 0013 14V7a1.5 1.5 0 00-1.5-1.5v-1A3.5 3.5 0 008 1zm2.5 4.5v-1a2.5 2.5 0 00-5 0v1h5zM4 7a.5.5 0 01.5-.5h7a.5.5 0 01.5.5v7a.5.5 0 01-.5.5h-7a.5.5 0 01-.5-.5V7z"></path></svg> Secure Connection</span>
                    <span class="flex items-center gap-2 cursor-default text-emerald-500"><svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M14 3.5v9a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-9a.5.5 0 01.5-.5h11a.5.5 0 01.5.5zm-1 1H3v7h10v-7zM8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg> Intercept Active <span id="progress-counter" class="ml-1">(1/?)</span></span>
                </div>

                <div class="outlook-main">
                    <div class="outlook-sidebar">
                        <div class="ol-nav-item active">
                            <svg class="ol-nav-icon fill-current" viewBox="0 0 16 16"><path d="M1 3.5a.5.5 0 01.5-.5h13a.5.5 0 01.5.5v9a.5.5 0 01-.5.5h-13a.5.5 0 01-.5-.5v-9zm1 1v7h12v-7H2zm3 2h6v1H5v-1z"></path></svg>
                            <span data-en="Inbox" data-ms="Peti Masuk">Inbox</span>
                        </div>
                        <div class="ol-nav-item">
                            <svg class="ol-nav-icon fill-current" viewBox="0 0 16 16"><path d="M8 1L1 4.5v3.13a6.83 6.83 0 003.54 5.92L8 15l3.46-1.45A6.83 6.83 0 0015 7.63V4.5L8 1zm6 3.9v2.73a5.84 5.84 0 01-3.03 5.07L8 13.8l-2.97-1.1A5.84 5.84 0 012 7.63V4.9l6-3 6 3z"></path></svg>
                            <span data-en="Quarantine" data-ms="Kuarantin">Quarantine</span>
                        </div>
                        <div class="ol-nav-item">
                            <svg class="ol-nav-icon fill-current" viewBox="0 0 16 16"><path d="M13.66 4l-1.32-1.33-6.4 6.41-3.6-3.61L1 6.8l4.94 4.95L13.66 4z"></path></svg>
                            <span data-en="Verified Safe" data-ms="Disahkan Selamat">Verified Safe</span>
                        </div>
                    </div>

                    <div class="outlook-list">
                        <div class="ol-list-header" data-en="Inbox" data-ms="Peti Masuk">Inbox</div>
                        <div class="ol-list-item active" id="list-preview-card">
                            <div class="ol-list-sender dynamic-translation" id="list-sender" data-original="--">--</div>
                            <div class="ol-list-subject dynamic-translation" id="list-subject" data-original="--">--</div>
                            <div class="ol-list-preview dynamic-translation" id="list-body-preview" data-original="--">--</div>
                        </div>
                        <div class="ol-list-item opacity-40">
                            <div class="ol-list-sender">IT Helpdesk</div>
                            <div class="ol-list-subject">System Update Completed</div>
                            <div class="ol-list-preview">Please restart your workstation...</div>
                        </div>
                        <div class="ol-list-item opacity-40">
                            <div class="ol-list-sender">HR Dept</div>
                            <div class="ol-list-subject">Holiday Schedule</div>
                            <div class="ol-list-preview">Attached is the updated holiday...</div>
                        </div>
                    </div>

                    <div class="outlook-reading">
                        <div class="ol-sec-banner">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M8 1a7 7 0 100 14A7 7 0 008 1zm-.5 3h1v5h-1V4zm0 6h1v1h-1v-1z"></path></svg>
                            <span data-en="Caution: This message originated from outside the organization." data-ms="Awas: Mesej ini berasal dari luar organisasi.">Caution: This message originated from outside the organization.</span>
                        </div>

                        <div class="ol-reading-header">
                            <div class="ol-reading-subject dynamic-translation" id="ui-subject" data-original="--">--</div>
                            <div class="ol-meta-row">
                                <div class="ol-avatar" id="ui-avatar">?</div>
                                <div class="ol-sender-details">
                                    <div class="ol-sender-name dynamic-translation" id="ui-sender" data-original="--">--</div>
                                    <div class="ol-sender-email font-mono">&lt;external_sender@domain.com&gt;</div>
                                </div>
                                <div class="ol-date" id="ui-date">Today, 09:41 AM</div>
                            </div>
                        </div>

                        <div class="ol-body dynamic-translation" id="ui-body" data-original="Initializing...">
                            Initializing...
                        </div>

                        <div class="ol-actions" id="action-buttons">
                            <button onclick="submitAnswer(false)" class="ol-btn ol-btn-safe">
                                <span data-en="✓ Mark as Safe" data-ms="✓ Tanda Selamat">✓ Mark as Safe</span>
                            </button>
                            <button onclick="submitAnswer(true)" class="ol-btn ol-btn-danger">
                                <span data-en="⚠ Flag as Phishing" data-ms="⚠ Tanda Phishing">⚠ Flag as Phishing</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="end-screen" class="terminal-box h-[650px] hidden flex-col items-center justify-center text-center p-8 bg-black/90">
            <h2 id="end-title" class="title-font text-5xl mb-4 uppercase tracking-wider text-emerald-500 drop-shadow-[0_0_15px_rgba(16,185,129,0.8)]" data-en="ROOM CLEARED" data-ms="BILIK SELESAI">ROOM CLEARED</h2>
            <p id="end-msg" class="text-gray-400 mb-8 max-w-md mx-auto leading-relaxed" data-en="Threats neutralized. System integrity maintained. You have earned a fragment of the master password." data-ms="Ancaman dineutralkan. Integriti sistem dikekalkan. Anda telah memperoleh serpihan kata laluan utama.">Threats neutralized. System integrity maintained. You have earned a fragment of the master password.</p>
            
            <div id="frag-box" class="w-24 h-24 border-2 border-emerald-500 flex items-center justify-center text-5xl font-bold text-white shadow-[0_0_20px_rgba(16,185,129,0.5)] mb-8 bg-emerald-900/30">
                R
            </div>

            <div class="flex flex-col gap-4">
                <a id="end-btn" href="#" class="bg-emerald-600 text-black px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-emerald-500 transition-colors shadow-[0_0_15px_rgba(16,185,129,0.4)] pointer-events-auto">
                    <span data-en="Return to Hub" data-ms="Kembali ke Hab">Return to Hub</span>
                </a>
                
                <a id="fail-return-btn" href="{{ route('agent.mission') }}" class="hidden bg-emerald-800 text-gray-300 px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-gray-700 transition-colors border border-gray-600 pointer-events-auto">
                    <span data-en="Return to Mission Control" data-ms="Kembali ke Papan Misi">Return to Mission Control</span>
                </a>
            </div>
        </div>

    </div>

    <script>
    // --- LOCAL ROOM MUSIC START ---
    const roomMusic = document.getElementById('room-music');

    function syncRoomMuteState(isMuted) {
        localStorage.setItem('shield_is_muted', isMuted ? 'true' : 'false');

        if (!roomMusic) return;

        roomMusic.muted = isMuted;

        if (isMuted) {
            roomMusic.pause();
        } else {
            roomMusic.volume = 0.5;
            roomMusic.play().catch(function() {
                console.log("Room music autoplay blocked, waiting for user interaction...");
            });
        }
    }

    function playRoomMusicIfAllowed() {
        if (!roomMusic) return;

        const isMuted = localStorage.getItem('shield_is_muted') === 'true';

        roomMusic.volume = 0.5;
        roomMusic.muted = isMuted;

        if (!isMuted) {
            roomMusic.play().catch(function() {
                console.log("Room music autoplay blocked, waiting for user interaction...");
            });
        }
    }

    window.addEventListener('DOMContentLoaded', function() {
        // Stop lobby music immediately when this room loads.
        if (window.parent && window.parent !== window) {
            window.parent.postMessage('stopMusic', '*');
            window.parent.postMessage({ type: 'requestMuteState' }, '*');
        }

        playRoomMusicIfAllowed();

        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 50);

        const launchBtn = document.getElementById('launch-btn');

        if (launchBtn) {
            launchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                startCountdown();
            });
        }
    });

    // Receive mute/unmute state from game-wrapper.
    window.addEventListener('message', function(e) {
        if (e.data && e.data.type === 'syncMute') {
            syncRoomMuteState(e.data.isMuted);
        }
    });

    // Start room music on first interaction if browser blocked autoplay.
    window.addEventListener('pointerdown', function() {
        playRoomMusicIfAllowed();
    }, { once: true });

    // --- IFRAME CURSOR SYNC ---
    window.addEventListener('pointermove', function(e) {
        if (window.parent && window.parent !== window) {
            window.parent.postMessage({
                type: 'iframeMouseMove',
                x: e.clientX,
                y: e.clientY
            }, '*');
        }
    });

    // --- Global Dictionary Cache for Translations ---
    window.translationCache = {};

    const enDictionary = {
        "Panduan Pra-Misi": "Pre-Mission Guidance",
        "Tutup & Mulakan Analisis": "Close & Begin Analysis"
    };

    const msDictionary = {
        "Pre-Mission Guidance": "Panduan Pra-Misi",
        "Close & Begin Analysis": "Tutup & Mulakan Analisis"
    };

    async function translateGoogleAPI(text, targetLang) {
        let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;

        try {
            let response = await fetch(url);
            let data = await response.json();
            let fullText = "";

            if (data && data[0]) {
                for (let i = 0; i < data[0].length; i++) {
                    if (data[0][i][0]) fullText += data[0][i][0];
                }
            }

            return fullText || text;
        } catch (e) {
            console.error("Translation Failed:", e);
            return text;
        }
    }

    async function processDynamicTranslations(lang) {
        const dynamicElements = document.querySelectorAll('.dynamic-translation');

        for (let el of dynamicElements) {
            let originalText = el.getAttribute('data-original');

            if (!originalText || originalText === '--') continue;

            let cacheKey = `${lang}_${originalText}`;

            if (window.translationCache[cacheKey]) {
                el.innerText = window.translationCache[cacheKey];
            } else {
                el.innerText = "Translating...";
                let translation = await translateGoogleAPI(originalText, lang);
                window.translationCache[cacheKey] = translation;
                el.innerText = translation;
            }
        }
    }

    // --- THEME & LANGUAGE LOGIC ---
    const bodyEl = document.body;
    const themeBtn = document.getElementById('theme-toggle');
    const langBtn = document.getElementById('lang-toggle');
    const translatables = document.querySelectorAll('[data-en]');

    let currentTheme = localStorage.getItem('shield_theme') || 'dark';
    let currentLang = localStorage.getItem('shield_lang') || 'en';

    if (currentTheme === 'light') {
        bodyEl.classList.add('light-mode');
        if (themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
    }

    function applyLanguage(lang) {
        if (langBtn) {
            langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
        }

        const dict = lang === 'en' ? enDictionary : msDictionary;

        document.querySelectorAll('.translation-target').forEach(function(el) {
            const text = el.textContent.trim();

            if (dict[text]) {
                el.textContent = dict[text];
            }
        });

        translatables.forEach(function(el) {
            if (el.getAttribute(`data-${lang}`)) {
                el.innerHTML = el.getAttribute(`data-${lang}`);
            }
        });

        if (
            document.getElementById('ui-body') &&
            document.getElementById('ui-body').getAttribute('data-original') !== "Initializing..."
        ) {
            processDynamicTranslations(lang);
        }
    }

    applyLanguage(currentLang);

    if (themeBtn) {
        themeBtn.addEventListener('click', function() {
            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('shield_theme', currentTheme);

            if (currentTheme === 'light') {
                bodyEl.classList.add('light-mode');
                themeBtn.innerHTML = '🌙 DARK MODE';
            } else {
                bodyEl.classList.remove('light-mode');
                themeBtn.innerHTML = '☀️ LIGHT MODE';
            }
        });
    }

    if (langBtn) {
        langBtn.addEventListener('click', function() {
            currentLang = currentLang === 'en' ? 'ms' : 'en';
            localStorage.setItem('shield_lang', currentLang);
            applyLanguage(currentLang);
        });
    }

    // --- GAME LOGIC ---
    const emails = @json($emails);

    let currentIndex = 0;
    let mistakes = 0;
    const MAX_MISTAKES = 3;

    let wrongAnswersLog = [];

    const uiSender = document.getElementById('ui-sender');
    const uiSubject = document.getElementById('ui-subject');
    const uiBody = document.getElementById('ui-body');
    const uiAvatar = document.getElementById('ui-avatar');

    const listSender = document.getElementById('list-sender');
    const listSubject = document.getElementById('list-subject');
    const listBodyPreview = document.getElementById('list-body-preview');

    const progressCounter = document.getElementById('progress-counter');
    const terminalBox = document.getElementById('game-terminal');
    const briefingScreen = document.getElementById('briefing-screen');
    const mainGameWrapper = document.getElementById('main-game-wrapper');

    const countdownScreen = document.getElementById('countdown-screen');
    const countdownNumber = document.getElementById('countdown-number');
    const lensFlare = document.getElementById('lens-flare');
    const flashBangContainer = document.getElementById('flash-bang-container');
    const tutorialModal = document.getElementById('tutorial-modal');

    const lives = document.querySelectorAll('.life-bar');

    const epicAudio = document.getElementById('epic-audio');
    const correctAudio = document.getElementById('correct-audio');
    const wrongAudio = document.getElementById('wrong-audio');

    function triggerEpicAnimation() {
        countdownNumber.classList.remove('epic-count');
        lensFlare.classList.remove('epic-flare');

        void countdownNumber.offsetWidth;
        void lensFlare.offsetWidth;

        countdownNumber.classList.add('epic-count');
        lensFlare.classList.add('epic-flare');
    }

    let isStarting = false;

    function startCountdown() {
        if (isStarting) return;

        isStarting = true;

        if (roomMusic && roomMusic.paused && !roomMusic.muted) {
            roomMusic.play().catch(function() {
                console.log("Audio play blocked");
            });
        }

        mainGameWrapper.style.display = 'none';

        countdownScreen.classList.remove('hidden');
        countdownScreen.classList.add('flex');

        if (epicAudio) {
            epicAudio.volume = 1.0;
            epicAudio.currentTime = 0;
            epicAudio.play().catch(function() {});
        }

        const numbers = ["3", "2", "1", "GO!"];
        let step = 0;

        function runCountdownStep() {
            if (step < numbers.length) {
                countdownNumber.textContent = numbers[step];

                if (step === 3) {
                    countdownNumber.classList.remove('text-emerald-500');
                    countdownNumber.classList.add('text-yellow-400');
                }

                triggerEpicAnimation();

                step++;
                setTimeout(runCountdownStep, 1000);
            } else {
                flashBangContainer.innerHTML = '<div class="white-flash"></div>';
                countdownScreen.classList.add('hidden');
                countdownScreen.classList.remove('flex');

                setTimeout(function() {
                    tutorialModal.classList.remove('hidden');
                    tutorialModal.classList.add('flex');

                    setTimeout(function() {
                        tutorialModal.classList.remove('opacity-0');
                    }, 50);
                }, 150);
            }
        }

        runCountdownStep();
    }

    function closeTutorialAndStart() {
        const tutVideo = document.getElementById('tutorial-video');

        if (tutVideo) tutVideo.pause();

        tutorialModal.classList.add('opacity-0');

        setTimeout(function() {
            tutorialModal.classList.add('hidden');
            tutorialModal.classList.remove('flex');

            mainGameWrapper.style.display = 'flex';

            briefingScreen.classList.add('hidden');
            briefingScreen.style.display = 'none';

            terminalBox.classList.remove('hidden');

            if (emails.length > 0) {
                loadEmail();
            } else {
                uiBody.innerText = currentLang === 'ms'
                    ? "RALAT SISTEM: TIADA MUATAN E-MEL DIJUMPAI. HUBUNGI PENTADBIR."
                    : "SYSTEM ERROR: NO EMAIL PAYLOADS FOUND IN DATABASE. ADMIN MUST CONFIGURE MODULE.";

                uiBody.classList.add('glitch-text', 'font-mono');
                document.getElementById('action-buttons').style.display = 'none';
            }
        }, 500);
    }

    function loadEmail() {
        if (currentIndex >= emails.length) {
            endGame(true);
            return;
        }

        const email = emails[currentIndex];

        const displayArea = document.querySelector('.outlook-reading');
        displayArea.classList.remove('fade-in');
        void displayArea.offsetWidth;
        displayArea.classList.add('fade-in');

        uiSender.setAttribute('data-original', email.sender_name_or_address);
        uiSubject.setAttribute('data-original', email.subject);
        uiBody.setAttribute('data-original', email.body);

        if (uiAvatar) {
            uiAvatar.innerText = email.sender_name_or_address.charAt(0).toUpperCase();
        }

        listSender.setAttribute('data-original', email.sender_name_or_address);
        listSubject.setAttribute('data-original', email.subject);

        let shortBody = email.body.length > 40
            ? email.body.substring(0, 40) + "..."
            : email.body;

        listBodyPreview.setAttribute('data-original', shortBody.replace(/\n/g, ' '));

        const dateObj = new Date();
        dateObj.setMinutes(dateObj.getMinutes() - Math.floor(Math.random() * 50));

        let hours = dateObj.getHours();
        let minutes = dateObj.getMinutes();
        let ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;

        document.getElementById('ui-date').innerText = `Today, ${hours}:${minutes} ${ampm}`;
        progressCounter.innerText = `(${currentIndex + 1}/${emails.length})`;

        processDynamicTranslations(currentLang);
    }

    let isSubmitting = false;

    function submitAnswer(playerGuessedPhishing) {
        if (isSubmitting) return;

        isSubmitting = true;

        setTimeout(function() {
            isSubmitting = false;
        }, 1500);

        const currentEmail = emails[currentIndex];
        const isActuallyPhishing = currentEmail.is_phishing == 1;

        if (playerGuessedPhishing === isActuallyPhishing) {
            if (correctAudio) {
                correctAudio.currentTime = 0;
                correctAudio.play();
            }

            currentIndex++;
            loadEmail();
        } else {
            if (wrongAudio) {
                wrongAudio.currentTime = 0;
                wrongAudio.play();
            }

            let guessedStatus = playerGuessedPhishing ? "Phishing" : "Safe";
            let actualStatus = isActuallyPhishing ? "Phishing" : "Safe";
            let emailSubject = currentEmail.subject || "Unknown Subject";

            wrongAnswersLog.push(
                `Email ${currentIndex + 1} ("${emailSubject}"): Marked as ${guessedStatus}, but was actually ${actualStatus}`
            );

            takeDamage();
        }
    }

    function takeDamage() {
        mistakes++;

        terminalBox.classList.remove('shake');
        void terminalBox.offsetWidth;
        terminalBox.classList.add('shake');

        if (mistakes <= MAX_MISTAKES) {
            const heart = lives[MAX_MISTAKES - mistakes];

            if (heart) {
                heart.classList.remove('text-emerald-500', 'drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]');
                heart.classList.add('text-gray-700', 'opacity-50');
            }
        }

        if (mistakes >= MAX_MISTAKES) {
            setTimeout(function() {
                endGame(false);
            }, 500);
        }
    }

    function logRoomFailure(roomNum) {
        fetch('{{ route('agent.log_failure') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                room_number: roomNum
            })
        })
        .then(function() {
            console.log("Failure recorded successfully.");
        })
        .catch(function(error) {
            console.error("Error logging failure:", error);
        });
    }

    function endGame(victory) {
        if (roomMusic) {
            let fadeRoomAudio = setInterval(function() {
                if (roomMusic.volume > 0.05) {
                    roomMusic.volume -= 0.05;
                } else {
                    roomMusic.pause();
                    clearInterval(fadeRoomAudio);
                }
            }, 100);
        }

        document.getElementById('game-terminal').classList.add('hidden');

        const endScreen = document.getElementById('end-screen');
        endScreen.classList.remove('hidden');
        endScreen.classList.add('flex');

        if (!victory) {
            const endTitle = document.getElementById('end-title');

            endTitle.setAttribute('data-en', 'YOU FAILED');
            endTitle.setAttribute('data-ms', 'ANDA GAGAL');
            endTitle.innerText = currentLang === 'ms' ? "ANDA GAGAL" : "YOU FAILED";

            endTitle.classList.replace('text-emerald-500', 'text-red-500');
            endTitle.classList.replace(
                'drop-shadow-[0_0_15px_rgba(16,185,129,0.8)]',
                'drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]'
            );

            const endMsg = document.getElementById('end-msg');

            endMsg.setAttribute(
                'data-en',
                "Please strengthen your understanding of this room's module and try again. You can do it!"
            );

            endMsg.setAttribute(
                'data-ms',
                "Sila mantapkan lagi kefahaman anda terhadap modul bilik ini dan cuba lagi. Anda pasti boleh!"
            );

            endMsg.innerText = currentLang === 'ms'
                ? "Sila mantapkan lagi kefahaman anda terhadap modul bilik ini dan cuba lagi. Anda pasti boleh!"
                : "Please strengthen your understanding of this room's module and try again. You can do it!";

            document.getElementById('frag-box').classList.add('hidden');

            let endBtn = document.getElementById('end-btn');

            if (!endBtn) {
                endBtn = endScreen.querySelector('a');
            }

            endBtn.innerHTML = currentLang === 'ms'
                ? '<span data-en="Retry Module" data-ms="Cuba Semula Modul">Cuba Semula Modul</span>'
                : '<span data-en="Retry Module" data-ms="Cuba Semula Modul">Retry Module</span>';

            endBtn.removeAttribute('onclick');
            endBtn.setAttribute('href', "javascript:location.reload()");

            endBtn.classList.replace('bg-emerald-600', 'bg-red-600');
            endBtn.classList.replace('hover:bg-emerald-500', 'hover:bg-red-500');
            endBtn.classList.replace(
                'shadow-[0_0_15px_rgba(16,185,129,0.4)]',
                'shadow-[0_0_15px_rgba(239,68,68,0.4)]'
            );

            const failReturnBtn = document.getElementById('fail-return-btn');

            if (failReturnBtn) {
                failReturnBtn.classList.remove('hidden');
                failReturnBtn.classList.add('inline-block');
            }

            endScreen.style.borderColor = "#ef4444";

            logRoomFailure(1);
        } else {
            let totalQuestions = emails.length;
            let correctAnswers = totalQuestions - mistakes;
            let finalScore = correctAnswers * 100;

            const encodedWrongs = encodeURIComponent(JSON.stringify(wrongAnswersLog));

            let proceedBtn = document.getElementById('end-btn');

            if (!proceedBtn) {
                proceedBtn = endScreen.querySelector('a');
            }

            proceedBtn.setAttribute(
                'href',
                `{{ route('agent.level1.complete') }}?score=${finalScore}&correct=${correctAnswers}&incorrect=${mistakes}&wrong_answers=${encodedWrongs}`
            );
        }

        applyLanguage(currentLang);
    }
</script>
    
    @include('partials.cursor')
</body>
</html>