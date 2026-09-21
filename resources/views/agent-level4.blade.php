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
    <title>S.H.I.E.L.D // The Mirror Web</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #001111; 
            --text-color: #d1d5db; 
            --card-bg: rgba(0, 20, 20, 0.95);
            --header-bg: #164e63;
        }

        .light-mode {
            --bg-color: #e0f2fe; 
            --text-color: #000000; 
            --card-bg: rgba(255, 255, 255, 0.95);
            --header-bg: #cffafe;
        }

        body { 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            font-family: 'Share Tech Mono', monospace; 
            overflow-x: hidden; 
            overflow-y: auto;
            transition: background-color 0.5s ease; 
        }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.15)); background-size: 100% 4px; pointer-events: none; }
        
        .game-container { max-width: 1000px; margin: 0 auto; min-height: 100vh; padding-top: 6rem; padding-bottom: 2rem; display: flex; flex-direction: column; justify-content: center; }
        
        .theme-card { background: var(--card-bg) !important; transition: background 0.5s ease; }
        .terminal-box { background: var(--card-bg); border: 1px solid #06b6d4; box-shadow: 0 0 30px rgba(6, 182, 212, 0.15); border-radius: 8px; position: relative; transition: background 0.5s ease; }
        .terminal-header { background: var(--header-bg); padding: 12px 20px; border-bottom: 1px solid #06b6d4; display: flex; justify-content: space-between; align-items: center; transition: background 0.5s ease; }
        
        .light-mode .lz-input { background-color: #ffffff; color: #000000 !important; border-color: #94a3b8; }
        .light-mode .lz-input:focus { border-color: #06b6d4; box-shadow: 0 0 10px rgba(6, 182, 212, 0.3); }
        .light-mode table { color: #000000; }
        .light-mode th { border-bottom-color: #cbd5e1; color: #ffffff !important; }
        .light-mode td { border-color: #e2e8f0; }
        .light-mode .bg-black\/40 { background-color: rgba(255, 255, 255, 0.9) !important; }
        .light-mode .border-blue-900\/50 { border-color: rgba(0, 0, 0, 0.15) !important; }
        
        /* 🚨 IMMUNE CLASSES FOR LIGHT MODE 🚨 */
        .always-white { color: #ffffff !important; }
        .always-black { color: #000000 !important; }
        #fail-return-btn, #fail-return-btn span { color: #ffffff !important; }

        .light-mode .text-gray-300, .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #000000 !important; }
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-cyan-500 { color: #0891b2 !important; } 

        /* 🚨 BOLD READABLE BADGES FOR LIGHT MODE 🚨 */
        .light-mode .feedback-badge-fail { 
            background-color: #ef4444 !important; 
            color: #ffffff !important; 
            border-color: #991b1b !important; 
        }
        .light-mode .feedback-badge-success { 
            background-color: #10b981 !important; 
            color: #ffffff !important; 
            border-color: #064e3b !important; 
        }
        
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

        .epic-bg { background: radial-gradient(circle at center, #0891b2 0%, #164e63 60%, #000000 100%); }
        .epic-text {
            font-family: 'Anton', sans-serif;
            background: linear-gradient(180deg, #cffafe 0%, #06b6d4 50%, #164e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0px 0px 15px rgba(6, 182, 212, 0.8));
        }

        .epic-count { animation: epicZoom 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes epicZoom {
            0% { transform: scale(1.8); opacity: 0; filter: brightness(2) drop-shadow(0 0 50px rgba(255,255,255,1)); }
            20% { opacity: 1; filter: brightness(1.5) drop-shadow(0 0 30px rgba(6, 182, 212, 0.8)); }
            100% { transform: scale(1); opacity: 1; filter: brightness(1) drop-shadow(0 0 10px rgba(6, 182, 212, 0.5)); }
        }

        .epic-flare { animation: flarePulse 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes flarePulse {
            0% { transform: scaleY(0) scaleX(0); opacity: 0; }
            10% { transform: scaleY(3) scaleX(1); opacity: 1; }
            100% { transform: scaleY(0) scaleX(3); opacity: 0; }
        }

        .particles { position: absolute; inset: 0; pointer-events: none; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 60px 60px; opacity: 0.15; animation: moveParticles 15s linear infinite; }
        @keyframes moveParticles { 0% { transform: translateY(0) scale(1); } 100% { transform: translateY(-100px) scale(1.2); } }

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

        /* --- SLOW BLINKING BUTTON ANIMATION --- */
        .btn-blink {
            animation: slowBlink 2.5s ease-in-out infinite;
        }
        .btn-blink:hover {
            animation: none; 
            opacity: 1;
            filter: brightness(1);
        }
        @keyframes slowBlink {
            0%, 100% { opacity: 1; filter: brightness(1); }
            50% { opacity: 0.5; filter: brightness(0.8); }
        }

        /* --- ZOOM IN POPUP ANIMATION --- */
        .zoom-in {
            animation: popZoom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes popZoom {
            0% { opacity: 0; transform: scale(0.7); }
            100% { opacity: 1; transform: scale(1); }
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

    <audio id="room-music" src="{{ asset('audio/Terran1.mp3') }}" loop preload="auto" crossorigin="anonymous" class="hidden"></audio>
    <audio id="epic-audio" preload="auto" class="hidden">
        <source src="{{ asset('audio/countdown-epic.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="correct-audio" preload="auto" class="hidden">
        <source src="{{ asset('audio/right-answer.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="wrong-audio" preload="auto" class="hidden">
        <source src="{{ asset('audio/wrong-answer.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto" class="hidden"></audio>

    <div class="fixed top-4 right-4 z-[9999999] flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-cyan-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-cyan-500 hover:text-white transition-colors text-cyan-500 shadow-[0_0_10px_rgba(6,182,212,0.3)] pointer-events-auto">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-cyan-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-cyan-500 hover:text-white transition-colors text-cyan-500 shadow-[0_0_10px_rgba(6,182,212,0.3)] pointer-events-auto">
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
        <div id="lens-flare" class="absolute w-[200%] h-2 bg-white shadow-[0_0_60px_20px_#22d3ee] opacity-0 -rotate-12 z-20 mix-blend-screen pointer-events-none"></div>
        <div id="countdown-number" class="relative z-10 text-[18rem] md:text-[25rem] epic-text pointer-events-none opacity-0">3</div>
    </div>

    <div id="tutorial-modal" class="hidden fixed inset-0 z-[99900] flex-col items-center justify-center p-4 transition-opacity duration-500 opacity-0 bg-black/80 backdrop-blur-md">
        <div class="theme-card border border-cyan-500/50 max-w-4xl w-full max-h-[95vh] overflow-y-auto p-6 md:p-8 rounded-xl shadow-[0_0_40px_rgba(6,182,212,0.3)] flex flex-col pointer-events-auto">
            
            <h2 class="text-cyan-500 text-2xl font-bold uppercase tracking-widest mb-4 flex items-center gap-3 shrink-0">
                <svg class="w-6 h-6 animate-pulse text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="translation-target" data-en="Pre-Mission Guidance" data-ms="Panduan Pra-Misi">Panduan Pra-Misi</span>
            </h2>
            
            <div class="relative w-full bg-black border border-gray-700 rounded-lg mb-6 overflow-hidden flex justify-center shadow-inner shrink-0" style="padding-bottom: 56.25%;">
                <video id="tutorial-video" controls class="absolute top-0 left-0 w-full h-full object-contain">
                    <source src="{{ asset('video/tutorial4.mp4') }}" type="video/mp4">
                </video>
            </div>
            
            <div class="bg-cyan-500/10 border-l-4 border-cyan-500 p-4 mb-6 rounded text-left shrink-0">
                <p class="theme-value font-mono text-sm leading-relaxed"    
                 data-ms="Sila teliti video panduan di atas. Beri perhatian penuh kepada struktur URL yang pelik, sijil keselamatan (SSL) yang hilang, dan penipuan visual pada reka bentuk halaman web. Tutup tetingkap ini untuk memulakan analisis forensik."
                 data-en="Please watch the video guide above. Pay full attention to strange URL structures, missing security certificates (SSL), and visual frauds on web page design. Close this window to start the forensic analysis.">
                 Sila teliti video panduan di atas. Beri perhatian penuh kepada struktur URL yang pelik, sijil keselamatan (SSL) yang hilang, dan penipuan visual pada reka bentuk halaman web. Tutup tetingkap ini untuk memulakan analisis forensik.
                </p>
            </div>
            
            <button onclick="closeTutorialAndStart()" class="relative z-50 w-full bg-cyan-600 hover:bg-cyan-500 text-black font-bold py-3 rounded uppercase tracking-widest shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all pointer-events-auto shrink-0">
                <span class="translation-target" data-en="Close & Begin Analysis" data-ms="Tutup & Mulakan Analisis">Tutup & Mulakan Analisis</span>
            </button>
        </div>
    </div>
    
    </div>

    <div id="feedback-overlay" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300">
        <div id="feedback-box" class="theme-card border-2 border-cyan-500/50 p-8 md:p-10 rounded-2xl max-w-2xl w-[90%] shadow-[0_0_40px_rgba(0,0,0,0.8)] zoom-in relative flex flex-col items-center text-center pointer-events-auto">
            
            <div class="flex flex-col items-center justify-center mb-6 border-b border-gray-700/50 pb-5 w-full">
                <p class="text-sm md:text-base uppercase tracking-widest text-cyan-500 font-bold mb-3" data-en="FORENSIC VERDICT" data-ms="KEPUTUSAN FORENSIK">FORENSIC VERDICT</p>
                <span id="feedback-status" class="text-sm md:text-base font-extrabold uppercase tracking-widest px-6 py-3 rounded shadow-lg"></span>
            </div>
            
            <p class="text-lg md:text-2xl font-bold text-gray-300 dynamic-translation mb-8 leading-relaxed" id="ui-explanation">--</p>
            
            <button onclick="nextScenario()" class="btn-blink w-full md:w-3/4 bg-cyan-600 hover:bg-cyan-500 text-black font-extrabold uppercase tracking-widest text-lg md:text-xl px-8 py-4 rounded-lg transition-colors shadow-[0_0_20px_rgba(6,182,212,0.5)] pointer-events-auto">
                <span data-en="Acknowledge & Proceed" data-ms="Sahkan & Teruskan">Acknowledge & Proceed</span>
            </button>
            
        </div>
    </div>

    <div class="game-container relative z-50 p-4 mt-8 md:mt-0" id="main-game-wrapper">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 px-2 gap-4 sc-anim sc-in-left">
            <div>
                <h1 class="title-font text-3xl text-cyan-500 tracking-widest uppercase mb-1 drop-shadow-[0_0_10px_rgba(6,182,212,0.8)]" data-en="S.H.I.E.L.D. FORENSICS" data-ms="FORENSIK S.H.I.E.L.D.">S.H.I.E.L.D. FORENSICS</h1>
                <p class="text-cyan-600 font-bold uppercase tracking-widest text-xs" data-en="Mission 04: The Mirror Web" data-ms="Misi 04: Jaringan Cermin">Mission 04: The Mirror Web</p>
            </div>
            
            <div class="flex items-center gap-3 bg-black/40 border border-cyan-900/50 px-4 py-2 rounded-lg backdrop-blur-sm self-start md:self-auto">
                <div class="text-gray-400 text-[10px] uppercase tracking-widest font-bold whitespace-nowrap" data-en="Analysis Integrity (Mistakes Allowed: 3)" data-ms="Integriti Analisis (Ralat Dibenarkan: 3)">
                    Analysis Integrity <span class="hidden sm:inline">(Mistakes Allowed: 3)</span>
                </div>
                <div class="flex gap-1" id="lives-container">
                    <svg class="w-6 h-6 text-cyan-500 drop-shadow-[0_0_10px_rgba(6,182,212,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-6 h-6 text-cyan-500 drop-shadow-[0_0_10px_rgba(6,182,212,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-6 h-6 text-cyan-500 drop-shadow-[0_0_10px_rgba(6,182,212,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
        </div>

        <div id="briefing-screen" class="terminal-box flex flex-col h-[650px] justify-center items-center p-8 text-center bg-black sc-anim sc-in-right tv-turn-on">
            <h2 class="title-font text-4xl mb-4 uppercase tracking-wider text-cyan-500 drop-shadow-[0_0_15px_rgba(6,182,212,0.8)]" data-en="MISSION BRIEFING" data-ms="TAKLIMAT MISI">MISSION BRIEFING</h2>
            
            <div class="max-w-2xl text-gray-400 space-y-6 mb-10 text-base md:text-lg font-bold leading-relaxed">
                <p data-en="Welcome to <span class='text-cyan-400 font-extrabold'>The Mirror Web</span>. Threat actors frequently create fake, visually identical websites to steal credentials." data-ms="Selamat datang ke <span class='text-cyan-400 font-extrabold'>Jaringan Cermin</span>. Penggodam kerap mencipta laman web palsu yang serupa untuk mencuri maklumat.">Welcome to <span class="text-cyan-400 font-extrabold">The Mirror Web</span>. Threat actors frequently create fake, visually identical websites to steal credentials.</p>
                <p data-en="Your objective is to perform forensic analysis on suspected domains. Verify the URL, SSL certificates, and visual clues to determine authenticity." data-ms="Objektif anda adalah untuk melakukan analisis forensik ke atas domain yang disyaki. Sahkan URL, sijil SSL, dan petunjuk visual untuk menentukan kesahihan.">Your objective is to perform forensic analysis on suspected domains. Verify the URL, SSL certificates, and visual clues to determine authenticity.</p>
                <p class="text-red-500 font-extrabold" data-en="WARNING: You are only permitted 3 mistakes before the system locks you out." data-ms="AMARAN: Anda hanya dibenarkan 3 ralat sebelum sistem mengunci anda.">WARNING: You are only permitted 3 mistakes before the system locks you out.</p>
                <p class="italic text-cyan-600 uppercase tracking-widest mt-4 font-extrabold" data-en="Good luck, Agent." data-ms="Semoga berjaya, Ejen.">Good luck, Agent.</p>
            </div>

            <button id="launch-btn" class="bg-cyan-600 text-black px-10 py-4 rounded font-bold text-xl uppercase tracking-widest hover:bg-cyan-500 transition-colors shadow-[0_0_20px_rgba(6,182,212,0.6)] cursor-pointer" style="position: relative; z-index: 99999; pointer-events: auto;">
                <span data-en="Launch Mission" data-ms="Mula Misi">Launch Mission</span>
            </button>
        </div>

        <div id="game-terminal" class="terminal-box flex flex-col h-[650px] hidden">
            <div class="terminal-header">
                <div class="text-cyan-400 font-extrabold tracking-widest uppercase text-sm md:text-base flex items-center gap-3">
                    <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.8)]"></span>
                    <span data-en="Scanning Target" data-ms="Mengimbas Sasaran">Scanning Target</span> <span id="progress-counter">(1/?)</span>
                </div>
                <div id="ui-ssl-badge" class="text-black bg-cyan-500 px-3 py-1 rounded text-[10px] uppercase tracking-widest font-bold" data-en="SSL UNKNOWN" data-ms="SSL TIDAK DIKETAHUI">SSL UNKNOWN</div>
            </div>

            <div class="flex-1 p-6 overflow-y-auto flex flex-col md:flex-row gap-6" id="scenario-display">
                
                <div class="w-full md:w-1/2 flex flex-col">
                    <div class="border border-cyan-900 bg-black/50 p-2 rounded flex-1 flex flex-col items-center justify-center relative group h-[300px] md:h-auto">
                        <img id="ui-image" src="" alt="Website Mockup" class="max-h-full max-w-full object-contain rounded opacity-90 transition-opacity group-hover:opacity-100 cursor-pointer pointer-events-auto" onclick="openFullscreen()">
                        <div class="absolute bottom-2 right-2 bg-black/80 text-cyan-500 text-[9px] px-2 py-1 rounded border border-cyan-900/50 uppercase tracking-widest pointer-events-none" data-en="Visual Data Rendered" data-ms="Data Visual Dipaparkan">Visual Data Rendered</div>
                    </div>
                    <button onclick="openFullscreen()" class="mt-3 w-full bg-cyan-900/40 hover:bg-cyan-800 text-cyan-400 border border-cyan-600/50 font-bold py-2 rounded text-xs uppercase tracking-widest transition-colors flex items-center justify-center gap-2 shadow-[0_0_10px_rgba(6,182,212,0.1)] pointer-events-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                        <span data-en="Enlarge Image" data-ms="Besarkan Imej">Enlarge Image</span>
                    </button>
                </div>

                <div class="w-full md:w-1/2 flex flex-col">
                    
                    <div class="bg-cyan-900/20 border border-cyan-900/50 p-4 rounded mb-4">
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold" data-en="Detected URL:" data-ms="URL Dikesan:">Detected URL:</p>
                        <p class="text-cyan-500 font-extrabold text-lg break-all" id="ui-url">--</p>
                    </div>

                    <div class="bg-cyan-900/20 border border-cyan-900/50 p-4 rounded mb-4 flex-1">
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold" data-en="Page Title:" data-ms="Tajuk Halaman:">Page Title:</p>
                        <p class="text-gray-300 font-extrabold mb-4 text-base" id="ui-title">--</p>
                        
                        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 font-bold" data-en="System Clues / Notes:" data-ms="Petunjuk Sistem / Nota:">System Clues / Notes:</p>
                        <p class="text-gray-400 text-base font-bold whitespace-pre-wrap leading-relaxed" id="ui-clues">--</p>
                    </div>

                    <div class="flex gap-4 mt-auto" id="action-buttons">
                        <button onclick="submitAnswer(false)" class="flex-1 bg-emerald-900/30 hover:bg-emerald-600 text-emerald-400 hover:text-white border border-emerald-600 font-bold py-3 rounded transition-all uppercase tracking-widest text-sm shadow-[0_0_10px_rgba(16,185,129,0.2)] pointer-events-auto">
                            <span data-en="Verify Legitimate" data-ms="Sahkan">Verify Legitimate</span>
                        </button>
                        <button onclick="submitAnswer(true)" class="flex-1 bg-red-900/30 hover:bg-red-600 text-red-500 hover:text-white border border-red-600 font-bold py-3 rounded transition-all uppercase tracking-widest text-sm shadow-[0_0_10px_rgba(220,38,38,0.2)] pointer-events-auto">
                            <span data-en="Quarantine Site" data-ms="Asingkan Web Ini">Quarantine Site</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div id="end-screen" class="terminal-box h-[650px] hidden flex-col items-center justify-center text-center p-8">
            <h2 id="end-title" class="title-font text-5xl mb-4 uppercase tracking-wider text-cyan-500 drop-shadow-[0_0_15px_rgba(6,182,212,0.8)]" data-en="NETWORK SECURED" data-ms="RANGKAIAN DISELAMATKAN">NETWORK SECURED</h2>
            <p id="end-msg" class="text-gray-400 mb-8 max-w-md mx-auto leading-relaxed" data-en="All malicious domains successfully quarantined. You have earned the fourth fragment of the master password." data-ms="Semua domain berniat jahat berjaya dikuarantin. Anda telah memperoleh serpihan keempat kata laluan utama.">All malicious domains successfully quarantined. You have earned the fourth fragment of the master password.</p>
            
            <div id="frag-box" class="w-24 h-24 border-2 border-cyan-500 flex items-center justify-center text-5xl font-bold text-white shadow-[0_0_20px_rgba(6,182,212,0.5)] mb-8 bg-cyan-900/30">
                H
            </div>

            <div class="flex flex-col gap-4">
                <a href="{{ route('agent.level4.complete') }}" id="end-btn" class="bg-cyan-600 text-black px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-cyan-500 transition-colors shadow-[0_0_15px_rgba(6,182,212,0.4)] btn-blink pointer-events-auto">
                    <span data-en="Proceed to Mainframe" data-ms="Teruskan ke Mainframe">Proceed to Mainframe</span>
                </a>
                
                <a id="fail-return-btn" href="{{ route('agent.mission') }}" class="hidden bg-gray-800 text-gray-300 px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-gray-700 transition-colors border border-gray-600 always-white pointer-events-auto">
                    <span data-en="Return to Mission Control" data-ms="Kembali ke Papan Misi" class="always-white">Return to Mission Control</span>
                </a>
            </div>
        </div>

    </div>

    <div id="fullscreen-overlay" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 transition-opacity duration-300 opacity-0" style="background: rgba(0, 5, 5, 0.95); backdrop-filter: blur(10px);">
        <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(6, 182, 212, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(6, 182, 212, 0.1) 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent">
            <div class="flex items-center gap-3 text-cyan-500">
                <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                <span class="font-bold tracking-widest uppercase text-sm" data-en="SURVEILLANCE MODE ACTIVE" data-ms="MOD PENGAWASAN AKTIF">SURVEILLANCE MODE ACTIVE</span>
            </div>
            <button onclick="closeFullscreen()" class="bg-black/50 text-gray-400 hover:text-cyan-400 border border-gray-700 hover:border-cyan-500 p-2 rounded transition-colors flex items-center gap-2 pointer-events-auto">
                <span class="text-xs uppercase tracking-widest font-bold hidden md:inline" data-en="Close Scanner" data-ms="Tutup Pengimbas">Close Scanner</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <img id="fullscreen-image" src="" alt="HD Target View" class="max-w-[95vw] max-h-[85vh] object-contain rounded border border-cyan-500/30 shadow-[0_0_50px_rgba(6,182,212,0.15)] relative z-10 transform scale-95 transition-transform duration-300">
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
            // Stop lobby music immediately when this room loads and sync state.
            if (window.parent && window.parent !== window) {
                window.parent.postMessage('stopMusic', '*');
                window.parent.postMessage({ type: 'requestMuteState' }, '*');
            }

            playRoomMusicIfAllowed();

            setTimeout(() => { document.body.classList.add('loaded'); }, 50);
            
            // Re-apply language for Level 3 specifics
            applyLanguage(currentLang);

            // 🔥 EXPLICIT BIND FOR LAUNCH BUTTON 🔥
            const launchBtn = document.getElementById('launch-btn');
            if(launchBtn) {
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

        // --- 1. GOOGLE API TRANSLATOR ---
        async function translateGoogleAPI(text, targetLang) {
            if (!text) return "";
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURI(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                return data[0].map(item => item[0]).join(''); 
            } catch(e) {
                console.error("Translation Failed:", e);
                return text; 
            }
        }

        // --- 2. THEME & LANGUAGE LOGIC ---
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        async function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    el.innerHTML = el.getAttribute(`data-${lang}`);
                }
            });

            if(scenarios && scenarios.length > 0 && !document.getElementById('game-terminal').classList.contains('hidden')) {
                await translateCurrentScenario(lang);
            }
        }

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

        // --- 3. GAMEPLAY VARIABLES & INITIALIZATION ---
        const scenarios = @json($scenarios);
        let currentIndex = 0;
        let mistakes = 0;
        const MAX_MISTAKES = 3;
        let wrongAnswersLog = [];
        let isStarting = false;

        const uiImage = document.getElementById('ui-image');
        const uiUrl = document.getElementById('ui-url');
        const uiTitle = document.getElementById('ui-title');
        const uiClues = document.getElementById('ui-clues');
        const uiSslBadge = document.getElementById('ui-ssl-badge');
        
        const progressCounter = document.getElementById('progress-counter');
        const terminalBox = document.getElementById('game-terminal');
        const briefingScreen = document.getElementById('briefing-screen');
        const countdownScreen = document.getElementById('countdown-screen');
        const countdownNumber = document.getElementById('countdown-number');
        const lensFlare = document.getElementById('lens-flare');
        const flashBangContainer = document.getElementById('flash-bang-container');
        const lives = document.querySelectorAll('.life-bar');
        const mainGameWrapper = document.getElementById('main-game-wrapper');
        const tutorialModal = document.getElementById('tutorial-modal');
        
        const actionBtns = document.getElementById('action-buttons');
        const feedbackOverlay = document.getElementById('feedback-overlay');
        const feedbackBox = document.getElementById('feedback-box');
        const statusBadge = document.getElementById('feedback-status');
        const uiExplanation = document.getElementById('ui-explanation');

        const fullscreenOverlay = document.getElementById('fullscreen-overlay');
        const fullscreenImage = document.getElementById('fullscreen-image');

        const epicAudio = document.getElementById('epic-audio');
        const correctAudio = document.getElementById('correct-audio');
        const wrongAudio = document.getElementById('wrong-audio');

        window.addEventListener('DOMContentLoaded', () => { 
            setTimeout(() => { document.body.classList.add('loaded'); }, 50);
            applyLanguage(currentLang);

            // 🔥 EXPLICITLY BIND THE BUTTON CLICK IN JS 🔥
            const launchBtn = document.getElementById('launch-btn');
            if(launchBtn) {
                launchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    startCountdown(this);
                });
            }
        });

        // --- 4. COUNTDOWN ENGINE ---
        function triggerEpicAnimation() {
            countdownNumber.classList.remove('epic-count');
            lensFlare.classList.remove('epic-flare');
            void countdownNumber.offsetWidth; 
            void lensFlare.offsetWidth;
            countdownNumber.classList.add('epic-count');
            lensFlare.classList.add('epic-flare');
        }

        function startCountdown(btnElement) {
            if (isStarting) return;
            isStarting = true;

            if (btnElement) {
                btnElement.disabled = true;
                btnElement.style.pointerEvents = 'none';
                btnElement.style.opacity = '0.5';
            }

            if (roomMusic.paused) {
                roomMusic.play().catch(e => console.log("Audio play blocked"));
            }

            // HIDE GAME CONTAINER
            mainGameWrapper.style.display = 'none';

            countdownScreen.classList.remove('hidden'); 
            countdownScreen.classList.add('flex'); 
            
            if(epicAudio) {
                epicAudio.volume = 1.0;
                epicAudio.currentTime = 0; 
                epicAudio.play().catch(()=>{});
            }

            const numbers = ["3", "2", "1", "GO!"];
            let step = 0;

            function runCountdownStep() {
                if (step < numbers.length) {
                    countdownNumber.textContent = numbers[step];
                    
                    if (step === 3) {
                        countdownNumber.classList.remove('text-cyan-500');
                        countdownNumber.classList.add('text-yellow-400');
                    }
                    
                    triggerEpicAnimation(); 
                    
                    step++;
                    setTimeout(runCountdownStep, 1000);
                } else {
                    flashBangContainer.innerHTML = '<div class="white-flash"></div>';
                    countdownScreen.classList.add('hidden');
                    countdownScreen.classList.remove('flex');
                    
                    setTimeout(() => {
                        tutorialModal.classList.remove('hidden');
                        tutorialModal.classList.add('flex');
                        setTimeout(() => tutorialModal.classList.remove('opacity-0'), 50);
                    }, 150);
                }
            }

            runCountdownStep();
        }

        function closeTutorialAndStart() {
            const tutVideo = document.getElementById('tutorial-video');
            if(tutVideo) tutVideo.pause();
            
            tutorialModal.classList.add('opacity-0');
            
            setTimeout(() => {
                tutorialModal.classList.add('hidden');
                tutorialModal.classList.remove('flex');
                
                mainGameWrapper.style.display = 'flex';
                
                briefingScreen.classList.add('hidden'); 
                briefingScreen.style.display = 'none';

                terminalBox.classList.remove('hidden');
                
                if(scenarios && scenarios.length > 0) {
                    loadScenario();
                } else {
                    uiTitle.innerText = "SYSTEM ERROR: NO TARGETS ACQUIRED.";
                    uiTitle.classList.add('glitch-text', 'font-mono');
                    document.getElementById('action-buttons').style.display = 'none';
                }
            }, 500);
        }

        // --- 5. DYNAMIC TRANSLATION INJECTOR ---
        async function translateCurrentScenario(lang) {
            const s = scenarios[currentIndex];
            if(!s) return;

            if (lang === 'en') {
                uiTitle.innerText = s.title;
                uiClues.innerText = s.clues;
            } else {
                uiTitle.innerText = "Translating...";
                uiClues.innerText = "Translating...";
                
                if(!s.title_ms) s.title_ms = await translateGoogleAPI(s.title, 'ms');
                if(!s.clues_ms) s.clues_ms = await translateGoogleAPI(s.clues, 'ms');
                
                uiTitle.innerText = s.title_ms;
                uiClues.innerText = s.clues_ms;
            }
        }

        async function loadScenario() {
            if (currentIndex >= scenarios.length) {
                endGame(true);
                return;
            }

            const s = scenarios[currentIndex];
            
            actionBtns.classList.remove('hidden');
            
            const displayArea = document.getElementById('scenario-display');
            displayArea.classList.remove('fade-in');
            void displayArea.offsetWidth;
            displayArea.classList.add('fade-in');

            uiImage.src = '/storage/' + s.image_path;
            uiUrl.innerText = s.url;
            
            if (s.has_ssl) {
                uiSslBadge.innerText = currentLang === 'en' ? "🔒 SSL SECURED" : "🔒 SSL DISELAMATKAN";
                uiSslBadge.className = "text-black bg-emerald-500 px-3 py-1 rounded text-[10px] uppercase tracking-widest font-bold";
            } else {
                uiSslBadge.innerText = currentLang === 'en' ? "⚠️ NO SSL DETECTED" : "⚠️ TIADA SSL DIKESAN";
                uiSslBadge.className = "text-black bg-yellow-500 px-3 py-1 rounded text-[10px] uppercase tracking-widest font-bold";
            }
            
            progressCounter.innerText = `(${currentIndex + 1}/${scenarios.length})`;

            await translateCurrentScenario(currentLang);
        }

        // --- FULLSCREEN LOGIC ---
        function openFullscreen() {
            fullscreenImage.src = uiImage.src; 
            fullscreenOverlay.classList.remove('hidden');
            fullscreenOverlay.classList.add('flex');
            
            setTimeout(() => {
                fullscreenOverlay.classList.remove('opacity-0');
                fullscreenImage.classList.remove('scale-95');
                fullscreenImage.classList.add('scale-100');
            }, 10);
        }

        function closeFullscreen() {
            fullscreenOverlay.classList.add('opacity-0');
            fullscreenImage.classList.remove('scale-100');
            fullscreenImage.classList.add('scale-95');
            
            setTimeout(() => {
                fullscreenOverlay.classList.add('hidden');
                fullscreenOverlay.classList.remove('flex');
            }, 300);
        }

        fullscreenOverlay.addEventListener('click', function(e) {
            if (e.target === fullscreenOverlay) closeFullscreen();
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !fullscreenOverlay.classList.contains('hidden')) closeFullscreen();
        });

        // --- CORE GAME LOGIC ---
        let isSubmitting = false;

        async function submitAnswer(playerGuessedPhishing) {
            if (isSubmitting) return;
            isSubmitting = true;

            setTimeout(() => { isSubmitting = false; }, 1500);

            const currentScenario = scenarios[currentIndex];
            const isActuallyPhishing = currentScenario.is_phishing == 1; 

            actionBtns.classList.add('hidden');
            
            let feedbackText = currentScenario.explanation || "Analysis complete.";
            
            if (currentLang === 'en') {
                uiExplanation.innerText = feedbackText;
            } else {
                uiExplanation.innerText = "Translating...";
                if(!currentScenario.explanation_ms) currentScenario.explanation_ms = await translateGoogleAPI(feedbackText, 'ms');
                uiExplanation.innerText = currentScenario.explanation_ms;
            }

            feedbackOverlay.classList.remove('hidden');
            feedbackOverlay.classList.add('flex');
            
            feedbackBox.classList.remove('zoom-in');
            void feedbackBox.offsetWidth;
            feedbackBox.classList.add('zoom-in');

            if (playerGuessedPhishing === isActuallyPhishing) {
                if(correctAudio) {
                    correctAudio.currentTime = 0;
                    correctAudio.play();
                }

                feedbackBox.classList.replace('border-cyan-500/50', 'border-emerald-500');
                feedbackBox.classList.replace('border-red-500', 'border-emerald-500');
                feedbackBox.style.boxShadow = "0 0 40px rgba(16, 185, 129, 0.4)";
                
                statusBadge.innerText = currentLang === 'en' ? "CORRECT ANALYSIS" : "ANALISIS TEPAT";
                statusBadge.className = "text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded bg-emerald-900/50 text-emerald-400 border border-emerald-500/50";
            } else {
                if(wrongAudio) {
                    wrongAudio.currentTime = 0;
                    wrongAudio.play();
                }

                feedbackBox.classList.replace('border-cyan-500/50', 'border-red-500');
                feedbackBox.classList.replace('border-emerald-500', 'border-red-500');
                feedbackBox.style.boxShadow = "0 0 40px rgba(239, 68, 68, 0.4)";
                
                statusBadge.innerText = currentLang === 'en' ? "INCORRECT ASSESSMENT" : "PENILAIAN SALAH";
                statusBadge.className = "text-[20px] font-bold uppercase tracking-widest px-2 py-1 rounded bg-white-900/50 text-red-900 border border-red-500/50";
                
                let guessedStatus = playerGuessedPhishing ? "Phishing/Threat" : "Legitimate/Safe";
                let actualStatus = isActuallyPhishing ? "Phishing/Threat" : "Legitimate/Safe";
                let siteUrl = currentScenario.url || "Unknown URL";
                wrongAnswersLog.push(`Scenario ${currentIndex + 1} ("${siteUrl}"): Marked as ${guessedStatus}, but was actually ${actualStatus}`);
                
                takeDamage();
            }
        }
        
        function nextScenario() {
            feedbackOverlay.classList.add('hidden');
            feedbackOverlay.classList.remove('flex');
            
            feedbackBox.className = "theme-card border-2 border-cyan-500/50 p-8 md:p-10 rounded-2xl max-w-2xl w-[90%] shadow-[0_0_40px_rgba(0,0,0,0.8)] zoom-in relative flex flex-col items-center text-center pointer-events-auto";
            feedbackBox.style.boxShadow = "";
            
            currentIndex++;
            loadScenario();
        }

        function takeDamage() {
            mistakes++;
            
            terminalBox.classList.remove('shake');
            void terminalBox.offsetWidth;
            terminalBox.classList.add('shake');
            
            if(mistakes <= MAX_MISTAKES) {
                const heart = lives[MAX_MISTAKES - mistakes];
                if (heart) {
                    heart.classList.remove('text-cyan-500', 'drop-shadow-[0_0_10px_rgba(6,182,212,0.8)]');
                    heart.classList.add('text-gray-700', 'opacity-50');
                }
            }

            if (mistakes >= MAX_MISTAKES) {
                setTimeout(() => { endGame(false); }, 1000); 
            }
        }

        // 🔥 SILENT LOGGER FUNCTION 🔥
        function logRoomFailure(roomNum) {
            fetch('{{ route('agent.log_failure') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ room_number: roomNum })
            })
            .then(response => console.log("Failure recorded successfully."))
            .catch(error => console.error("Error logging failure:", error));
        }

        function endGame(victory) {
            feedbackOverlay.classList.add('hidden');
            feedbackOverlay.classList.remove('flex');

            let fadeRoomAudio = setInterval(function () {
                if (roomMusic.volume > 0.05) {
                    roomMusic.volume -= 0.05;
                } else {
                    roomMusic.pause();
                    clearInterval(fadeRoomAudio);
                }
            }, 100);

            document.getElementById('game-terminal').classList.add('hidden');
            const endScreen = document.getElementById('end-screen');
            endScreen.classList.remove('hidden');
            endScreen.classList.add('flex');

            if (!victory) {
                const endTitle = document.getElementById('end-title');
                endTitle.setAttribute('data-en', 'YOU FAILED');
                endTitle.setAttribute('data-ms', 'ANDA GAGAL');
                endTitle.innerText = currentLang === 'ms' ? "ANDA GAGAL" : "YOU FAILED";
                endTitle.classList.replace('text-cyan-500', 'text-red-500');
                endTitle.classList.replace('drop-shadow-[0_0_15px_rgba(6,182,212,0.8)]', 'drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]');
                
                const endMsg = document.getElementById('end-msg');
                endMsg.setAttribute('data-en', "Please strengthen your understanding of this room's module and try again. You can do it!");
                endMsg.setAttribute('data-ms', "Sila mantapkan lagi kefahaman anda terhadap modul bilik ini dan cuba lagi. Anda pasti boleh!");
                endMsg.innerText = currentLang === 'ms' 
                    ? "Sila mantapkan lagi kefahaman anda terhadap modul bilik ini dan cuba lagi. Anda pasti boleh!" 
                    : "Please strengthen your understanding of this room's module and try again. You can do it!";
                
                document.getElementById('frag-box').classList.add('hidden');
                
                let endBtn = document.getElementById('end-btn');
                if(!endBtn) endBtn = endScreen.querySelector('a');
                
                endBtn.innerHTML = currentLang === 'ms' ? '<span data-en="Retry Module" data-ms="Cuba Semula Modul">Cuba Semula Modul</span>' : '<span data-en="Retry Module" data-ms="Cuba Semula Modul">Retry Module</span>';
                
                endBtn.removeAttribute('onclick');
                endBtn.setAttribute('href', "javascript:location.reload()");
                
                endBtn.classList.replace('bg-cyan-600', 'bg-red-600');
                endBtn.classList.replace('hover:bg-cyan-500', 'hover:bg-red-500');
                endBtn.classList.replace('shadow-[0_0_15px_rgba(6,182,212,0.4)]', 'shadow-[0_0_15px_rgba(239,68,68,0.4)]');
                
                const failReturnBtn = document.getElementById('fail-return-btn');
                if (failReturnBtn) {
                    failReturnBtn.classList.remove('hidden');
                    failReturnBtn.classList.add('inline-block');
                }
                
                endScreen.style.borderColor = "#ef4444";
                
                logRoomFailure(4);
                
            } else {
                let totalScenarios = scenarios.length;
                let correctAnswers = totalScenarios - mistakes; 
                let finalScore = correctAnswers * 100; 
                
                const encodedWrongs = encodeURIComponent(JSON.stringify(wrongAnswersLog));
                
                let proceedBtn = document.getElementById('end-btn');
                if(!proceedBtn) proceedBtn = endScreen.querySelector('a');
                
                proceedBtn.setAttribute('href', `{{ route('agent.level4.complete') }}?score=${finalScore}&correct=${correctAnswers}&incorrect=${mistakes}&wrong_answers=${encodedWrongs}`);
            }
            
            applyLanguage(currentLang);
        }
    </script>
    
@include('partials.cursor')
</body>
</html>