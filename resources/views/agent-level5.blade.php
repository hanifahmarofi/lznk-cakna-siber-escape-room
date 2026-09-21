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
    <title>S.H.I.E.L.D // MAINFRAME BOSS</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #0a0000;
            --text-color: #d1d5db;
            --card-bg: rgba(20, 0, 0, 0.95);
            --border-color: #ef4444;
            --shadow-color: rgba(239, 68, 68, 0.3);
            --header-bg: #7f1d1d;
            --accent-text: #f87171; 
            --streak-text: #fb923c; 
            --btn-true-text: #34d399; 
            --btn-false-text: #f87171; 
            --btn-true-bg: rgba(6, 78, 59, 0.4); 
            --btn-false-bg: rgba(127, 29, 29, 0.4); 
            
            --title-color: #ef4444;
            --value-color: #ffffff;
        }

        .light-mode {
            --bg-color: #fef2f2;
            --text-color: #450a0a;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: #dc2626;
            --shadow-color: rgba(220, 38, 38, 0.2);
            --header-bg: #fca5a5;
            --accent-text: #991b1b; 
            --streak-text: #c2410c; 
            --btn-true-text: #059669; 
            --btn-false-text: #dc2626; 
            --btn-true-bg: rgba(16, 185, 129, 0.15); 
            --btn-false-bg: rgba(239, 68, 68, 0.15); 
            
            --title-color: #dc2626;
            --value-color: #0f172a;
        }

        body { 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            font-family: 'Share Tech Mono', monospace; 
            overflow-x: hidden; 
            overflow-y: auto;
            transition: all 0.3s ease; 
        }
        
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        .theme-accent { color: var(--accent-text); transition: color 0.3s ease; }
        .theme-streak { color: var(--streak-text); transition: color 0.3s ease; }

        .game-container { max-width: 1000px; margin: 0 auto; min-height: 100vh; padding-top: 6rem; padding-bottom: 2rem; display: flex; flex-direction: column; justify-content: center; }
        
        .terminal-box { background: var(--card-bg); border: 2px solid var(--border-color); box-shadow: 0 0 40px var(--shadow-color); border-radius: 8px; overflow: hidden; position: relative; transition: all 0.3s ease; }
        .terminal-header { background: var(--header-bg); padding: 12px 20px; border-bottom: 2px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; }
        
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; }
        .theme-title { color: var(--title-color) !important; transition: color 0.3s ease; }
        .theme-value { color: var(--value-color) !important; transition: color 0.3s ease; }

        .shake-damage { animation: shakeDamage 0.4s cubic-bezier(.36,.07,.19,.97) both; border-color: #b91c1c !important; }
        @keyframes shakeDamage {
            10%, 90% { transform: translate3d(-4px, 0, 0); }
            20%, 80% { transform: translate3d(6px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-12px, 0, 0); }
            40%, 60% { transform: translate3d(12px, 0, 0); }
        }

        .epic-bg { background: radial-gradient(circle at center, #991b1b 0%, #450a0a 60%, #000000 100%); }
        .epic-text {
            font-family: 'Anton', sans-serif;
            background: linear-gradient(180deg, #fee2e2 0%, #ef4444 50%, #7f1d1d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0px 0px 15px rgba(239, 68, 68, 0.8));
        }

        .epic-count { animation: epicZoom 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes epicZoom {
            0% { transform: scale(1.8); opacity: 0; filter: brightness(2) drop-shadow(0 0 50px rgba(255,255,255,1)); }
            20% { opacity: 1; filter: brightness(1.5) drop-shadow(0 0 30px rgba(239, 68, 68, 0.8)); }
            100% { transform: scale(1); opacity: 1; filter: brightness(1) drop-shadow(0 0 10px rgba(239, 68, 68, 0.5)); }
        }

        .epic-flare { animation: flarePulse 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes flarePulse {
            0% { transform: scaleY(0) scaleX(0); opacity: 0; }
            10% { transform: scaleY(3) scaleX(1); opacity: 1; }
            100% { transform: scaleY(0) scaleX(3); opacity: 0; }
        }

        .particles { position: absolute; inset: 0; pointer-events: none; background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 60px 60px; opacity: 0.15; animation: moveParticles 15s linear infinite; }
        @keyframes moveParticles { 0% { transform: translateY(0) scale(1); } 100% { transform: translateY(-100px) scale(1.2); } }

        .sc-anim { opacity: 0; transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .sc-in-left { transform: translateX(-100vw); }
        .sc-in-right { transform: translateX(100vw); }
        body.loaded .sc-anim { opacity: 1; transform: translate(0, 0); }

        .shake-1 { animation: mildShake 0.2s infinite; }
        .shake-2 { animation: violentShake 0.1s infinite; filter: drop-shadow(0 0 20px red); }
        .white-flash { position: fixed; inset: 0; background: white; z-index: 99999; animation: flashBang 0.8s forwards; pointer-events: none; }
        
        @keyframes mildShake { 0% { transform: translate(1px, 1px) rotate(0deg); } 50% { transform: translate(-1px, -2px) rotate(-1deg); } 100% { transform: translate(1px, -1px) rotate(1deg); } }
        @keyframes violentShake { 0% { transform: translate(3px, 3px) rotate(0deg) scale(1.1); } 50% { transform: translate(-3px, -3px) rotate(-2deg) scale(1.1); } 100% { transform: translate(3px, -3px) rotate(2deg) scale(1.1); } }
        @keyframes flashBang { 0% { opacity: 1; } 100% { opacity: 0; } }

        .cert-glow { text-shadow: 0 0 20px rgba(16,185,129,0.8), 0 0 40px rgba(16,185,129,0.5); }
        
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.15)); background-size: 100% 4px; pointer-events: none; }

        /* 🤖 GOOGLE TRANSLATE OVERRIDES */
        iframe.skiptranslate { display: none !important; } 
        .goog-te-banner-frame { display: none !important; }
        .goog-te-menu-value { display: none !important; }
        .VIpgJd-Zvi9od-ORHb-OEVmcd { display: none !important; } 
        body { top: 0px !important; position: relative; }
        #google_translate_element { display: none !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
    </style>
</head>

<body class="relative">

    <div id="google_translate_element"></div>

    <audio id="room-music" src="{{ asset('audio/Terran2.mp3') }}" loop preload="auto" crossorigin="anonymous"></audio>

    <audio id="epic-audio" preload="auto"><source src="{{ asset('audio/countdown-epic.mp3') }}" type="audio/mpeg"></audio>
    <audio id="correct-audio" preload="auto"><source src="{{ asset('audio/right-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="wrong-audio" preload="auto"><source src="{{ asset('audio/wrong-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="hit1-audio" preload="auto"><source src="{{ asset('audio/first-transform.mp3') }}" type="audio/mpeg"></audio>
    <audio id="hit2-audio" preload="auto"><source src="{{ asset('audio/second-transform.mp3') }}" type="audio/mpeg"></audio>
    <audio id="explode-audio" preload="auto"><source src="{{ asset('audio/explosion.mp3') }}" type="audio/mpeg"></audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-60 filter sepia-[100%] hue-rotate-[300deg] saturate-[500%]">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-red-950 mix-blend-multiply opacity-80"></div>
    </div>

    <div class="fixed top-4 right-4 z-[9999999] flex gap-3 sc-anim sc-in-right">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-red-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-red-500 hover:text-white transition-colors shadow-[0_0_10px_rgba(239,68,68,0.3)] text-red-500">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-red-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-red-500 hover:text-white transition-colors shadow-[0_0_10px_rgba(239,68,68,0.3)] text-red-500">
            ☀️ LIGHT MODE
        </button>

    <a href="{{ route('agent.mission') }}" class="px-5 py-2 theme-card border border-purple-500/50 theme-title rounded hover:bg-purple-600 hover:text-white transition-all text-[10px] md:text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(147,51,234,0.3)] flex items-center justify-center gap-2">
                <span data-en="ABORT" data-ms="BATAL">ABORT</span>
            </a>

    </div>

    <div id="flash-bang-container" class="fixed inset-0 z-[99995] pointer-events-none"></div>

    <div id="countdown-screen" class="hidden fixed inset-0 z-[99990] flex items-center justify-center epic-bg overflow-hidden">
        <div class="particles"></div>
        <div id="lens-flare" class="absolute w-[200%] h-2 bg-white shadow-[0_0_60px_20px_#fca5a5] opacity-0 -rotate-12 z-20 mix-blend-screen pointer-events-none"></div>
        <div id="countdown-number" class="relative z-10 text-[18rem] md:text-[25rem] epic-text pointer-events-none opacity-0">3</div>
    </div>

    <div id="tutorial-modal" class="hidden fixed inset-0 z-[99900] flex-col items-center justify-center p-4 transition-opacity duration-500 opacity-0 bg-black/90 backdrop-blur-md">
        <div class="theme-card border border-red-500/50 max-w-4xl w-full max-h-[95vh] overflow-y-auto p-6 md:p-8 rounded-xl shadow-[0_0_40px_rgba(239,68,68,0.3)] flex flex-col">
            <h2 class="theme-title text-2xl font-bold uppercase tracking-widest mb-4 flex items-center gap-3 shrink-0">
                <svg class="w-6 h-6 animate-pulse text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="translation-target" data-en="Pre-Mission Guidance" data-ms="Panduan Pra-Misi">Panduan Pra-Misi</span>
            </h2>
            
            <div class="relative w-full bg-black border border-gray-700 rounded-lg mb-6 overflow-hidden flex justify-center shadow-inner shrink-0" style="padding-bottom: 56.25%;">
                <video id="tutorial-video" controls class="absolute top-0 left-0 w-full h-full object-contain">
                    <source src="{{ asset('video/tutorial5.mp4') }}" type="video/mp4">
                </video>
            </div>
            
            <div class="bg-red-500/10 border-l-4 border-red-500 p-4 mb-6 rounded text-left shrink-0">
                <p class="theme-value font-mono text-sm leading-relaxed"    
                 data-ms="Sila tonton video panduan di atas. Misi ini direka untuk menguji tahap kepantasan dan ketepatan anda dalam menganalisis ancaman keselamatan secara drastik. Sila bersedia untuk bertindak di bawah tekanan masa. Tutup tetingkap ini apabila anda sudah bersedia."
                 data-en="Please watch the video guide above. These missions are designed to test your level of speed and accuracy in drastically analyzing security threats. Please be prepared to act under time pressure. Close this window when you are ready.">
                 Sila tonton video panduan di atas. Misi ini direka untuk menguji tahap kepantasan dan ketepatan anda dalam menganalisis ancaman keselamatan secara drastik. Sila bersedia untuk bertindak di bawah tekanan masa. Tutup tetingkap ini apabila anda sudah bersedia.
                </p>
            </div>
            
            <button onclick="closeTutorialAndStart()" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-3 rounded uppercase tracking-widest shadow-[0_0_15px_rgba(239,68,68,0.4)] transition-all shrink-0">
                <span class="translation-target" data-en="Close & Begin Analysis" data-ms="Tutup & Mulakan Analisis">Tutup & Mulakan Analisis</span>
            </button>
        </div>
    </div>
    
    <div class="game-container relative z-10 p-4" id="main-game-wrapper">
        
        <div id="hud-section" class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 px-2 gap-4 sc-anim sc-in-left hidden transition-opacity duration-1000">
            <div>
                <h1 class="title-font text-3xl md:text-4xl text-red-500 tracking-widest uppercase mb-2 drop-shadow-[0_0_15px_rgba(239,68,68,0.8)] animate-pulse" data-en="MAINFRAME OVERRIDE" data-ms="PINTASAN MAINFRAME">MAINFRAME OVERRIDE</h1>
                <div class="flex flex-wrap gap-3 md:gap-4 theme-accent font-bold uppercase tracking-widest text-xs">
                    <span id="ui-global-timer">TIMER: --:--</span>
                    <span id="ui-score">SCORE: 0</span>
                    <span id="ui-streak" class="theme-streak hidden">STREAK x1.0</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3 bg-black/40 border border-red-900/50 px-4 py-2 rounded-lg backdrop-blur-sm self-start md:self-auto">
                <div class="text-gray-400 text-[10px] uppercase tracking-widest font-bold whitespace-nowrap" data-en="System Integrity (Mistakes Allowed: 3)" data-ms="Integriti Sistem (Kesilapan Dibenarkan: 3)">
                    System Integrity <span class="hidden sm:inline">(Mistakes Allowed: 3)</span>
                </div>
                <div class="flex gap-1" id="lives-container">
                    <svg class="w-5 h-5 text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-5 h-5 text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-5 h-5 text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
        </div>

        <div id="briefing-screen" class="terminal-box flex flex-col h-[600px] justify-center items-center p-8 text-center sc-anim sc-in-right tv-turn-on">
            <h2 class="title-font text-5xl mb-4 uppercase tracking-wider text-red-500 drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]" data-en="MISSION BRIEFING" data-ms="TAKLIMAT MISI">MISSION BRIEFING</h2>
            
            <div class="max-w-3xl opacity-90 space-y-6 mb-10 text-base md:text-lg font-semibold leading-relaxed">
                <p class="theme-value" data-en="Welcome to the <span class='text-red-500 font-extrabold'>S.H.I.E.L.D. Mainframe</span>. This is the final test of your cyber awareness." data-ms="Selamat datang ke <span class='text-red-500 font-extrabold'>Mainframe S.H.I.E.L.D.</span> Ini merupakan ujian terakhir bagi menguji tahap kesedaran siber anda.">
                    Welcome to the <span class='text-red-500 font-extrabold'>S.H.I.E.L.D. Mainframe</span>. This is the final test of your cyber awareness.
                </p>
                <p class="theme-value" data-en="Your objective is to analyze rapid-fire security statements. Determine if they are TRUE or FALSE before the timer expires." data-ms="Objektif anda adalah untuk menganalisis kenyataan keselamatan secara pantas. Tentukan sama ada ia BENAR atau PALSU sebelum masa tamat.">
                    Your objective is to analyze rapid-fire security statements. Determine if they are TRUE or FALSE before the timer expires.
                </p>
                
                <p class="text-red-500 font-extrabold text-lg md:text-xl drop-shadow-[0_0_5px_rgba(239,68,68,0.3)]" data-en="WARNING: Maximum security engaged. You only have 30 seconds per question and 3 mistakes allowed." data-ms="AMARAN: Mod keselamatan maksimum telah diaktifkan. Anda hanya mempunyai 30 saat bagi setiap soalan dan 3 kesilapan sahaja dibenarkan.">
                    WARNING: Maximum security engaged. You only have 30 seconds per question and 3 mistakes allowed.
                </p>
                
                <p class="italic text-red-600 uppercase tracking-widest mt-4 font-bold text-lg" data-en="Good luck, Agent." data-ms="Semoga berjaya, Ejen.">Good luck, Agent.</p>
            </div>

            <button onclick="startCountdown(this)" class="bg-red-600 text-white px-12 py-5 rounded font-extrabold text-2xl uppercase tracking-widest hover:bg-red-500 transition-colors shadow-[0_0_20px_rgba(239,68,68,0.6)]">
                <span data-en="Launch Mission" data-ms="Mulakan Misi">Launch Mission</span>
            </button>
        </div>

        <div id="game-terminal" class="terminal-box flex flex-col h-[600px] transition-opacity duration-1000 hidden">
            <div class="terminal-header">
                <div class="theme-accent font-bold tracking-widest uppercase text-xs flex items-center gap-2">
                    <span class="w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                    <span data-en="Rapid-Fire Protocol" data-ms="Protokol Kuiz Kilat">Rapid-Fire Protocol</span> <span id="progress-counter">(1/30)</span>
                </div>
                <div id="ui-q-timer" class="text-white bg-red-600 px-4 py-1 rounded text-lg uppercase tracking-widest font-bold shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-colors duration-300">10.0s</div>
            </div>

            <div class="flex-1 p-8 flex flex-col justify-center items-center text-center">
                <p class="text-[10px] text-red-500 mb-6 uppercase tracking-widest font-bold" data-en="ANALYZE STATEMENT:" data-ms="ANALISIS KENYATAAN BERIKUT:">ANALYZE STATEMENT:</p>
                <div class="text-2xl md:text-4xl leading-relaxed mb-12 max-w-3xl font-bold transition-colors theme-value" style="filter: drop-shadow(0 0 5px var(--shadow-color));" id="ui-question">
                    INITIALIZING MAINFRAME...
                </div>
                
                <div class="flex gap-6 w-full max-w-xl" id="action-buttons">
                    <button onclick="submitAnswer(1)" class="flex-1 hover:bg-emerald-600 hover:text-white border-2 border-emerald-600 font-bold py-6 rounded transition-all uppercase tracking-widest text-2xl shadow-[0_0_20px_rgba(16,185,129,0.3)]" style="background-color: var(--btn-true-bg); color: var(--btn-true-text);">
                        <span data-en="TRUE" data-ms="BENAR">TRUE</span>
                    </button>
                    <button onclick="submitAnswer(0)" class="flex-1 hover:bg-red-600 hover:text-white border-2 border-red-600 font-bold py-6 rounded transition-all uppercase tracking-widest text-2xl shadow-[0_0_20px_rgba(220,38,38,0.3)]" style="background-color: var(--btn-false-bg); color: var(--btn-false-text);">
                        <span data-en="FALSE" data-ms="PALSU">FALSE</span>
                    </button>
                </div>
            </div>
        </div>

        <div id="box-screen" class="hidden absolute inset-0 z-50 flex-col items-center justify-center bg-black/95 transition-opacity duration-1000 opacity-0">
            <h2 class="title-font text-3xl mb-8 uppercase tracking-wider text-emerald-500 drop-shadow-[0_0_10px_rgba(16,185,129,0.8)]" data-en="FINAL MISSION ACCOMPLISHED" data-ms="MISI TERAKHIR BERJAYA DITAMATKAN">ENCRYPTION CORE SECURED</h2>
            <p class="text-gray-400 text-lg font-bold mb-12 uppercase tracking-widest animate-pulse" data-en="You have completed the mission. Tap box 3 times to unlock." data-ms="Anda telah berjaya menamatkan misi ini. Ketik kotak 3 kali untuk buka.">You have completed the mission. Tap box 3 times to unlock.</p>
            
            <img id="shield-box" src="{{ asset('img/shield-box.png') }}" alt="S.H.I.E.L.D Box" class="w-64 md:w-96 cursor-pointer drop-shadow-[0_0_30px_rgba(16,185,129,0.4)] transition-transform hover:scale-105 active:scale-95">
        </div>

        <div id="reveal-screen" class="hidden absolute inset-0 z-50 flex-col items-center justify-center bg-black text-center p-8 transition-opacity duration-1000 opacity-0">
            <h1 class="title-font text-5xl md:text-7xl text-emerald-500 mb-4 cert-glow uppercase tracking-wider" data-en="MAINFRAME SECURED" data-ms="MAINFRAME DISELAMATKAN">MAINFRAME SECURED</h1>
            
            <div class="bg-emerald-950/30 border border-emerald-500/50 p-6 md:p-10 rounded-lg w-full max-w-3xl mb-8 relative overflow-hidden">
                <div class="scanlines absolute inset-0 pointer-events-none opacity-40"></div>
                <p class="text-emerald-400 text-sm md:text-base uppercase tracking-widest mb-6 font-bold relative z-10" data-en="Final Master Password Compiled:" data-ms="Kata Laluan Utama Berjaya Disusun:">Final Master Password Compiled:</p>
                
                <div class="flex justify-center gap-2 md:gap-4 mb-8 relative z-10">
                    <div class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50">R</div>
                    <div class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50">E</div>
                    <div class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50">C</div>
                    <div class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50">H</div>
                    
                    <div id="letter-E" class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50 opacity-0 transform scale-150 transition-all duration-500">E</div>
                    <div id="letter-C" class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50 opacity-0 transform scale-150 transition-all duration-500">C</div>
                    <div id="letter-K" class="w-10 h-14 md:w-16 md:h-20 border border-emerald-500 flex items-center justify-center text-3xl md:text-5xl font-bold text-white shadow-[0_0_15px_rgba(16,185,129,0.5)] bg-emerald-900/50 opacity-0 transform scale-150 transition-all duration-500">K</div>
                </div>

                <div id="meaning-text" class="opacity-0 transition-opacity duration-1000 relative z-10">
                    <p class="text-white italic text-base md:text-lg px-4 leading-relaxed" data-en="&quot;In cybersecurity, to <span class='text-emerald-400 font-bold'>RECHECK</span> is to survive. Always verify, never blindly trust.&quot;" data-ms="&quot;Dalam keselamatan siber, sikap sentiasa MENYEMAK SEMULA (<span class='text-emerald-400 font-bold'>RECHECK</span>) adalah kunci kelangsungan. Sentiasa sahkan, jangan mudah percaya.&quot;">"In cybersecurity, to <span class="text-emerald-400 font-bold">RECHECK</span> is to survive. Always verify, never blindly trust."</p>
                </div>
            </div>

            @if(isset($progress) && !$progress->level_5_completed)
            <div id="score-text" class="opacity-0 transition-opacity duration-1000 mb-8 flex flex-col md:flex-row gap-8 items-center justify-center">
                <div class="text-xl md:text-2xl font-bold text-gray-400"><span data-en="ROOM 5 SCORE:" data-ms="MARKAH BILIK 5:">ROOM 5 SCORE:</span> <span id="room-score-display" class="text-white">0</span></div>
                <div class="hidden md:block w-px h-8 bg-emerald-500/50"></div>
                <div class="text-2xl md:text-3xl font-bold text-white"><span data-en="TOTAL MARKS:" data-ms="JUMLAH KESELURUHAN MARKAH:">TOTAL MARKS:</span> 
                    <span id="final-score-display" class="text-emerald-500 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]">0</span>
                    <span class="text-gray-500 text-xl font-normal">/ {{ $trueMaxScore}}</span>
                </div>
            </div>
            @endif

            <button type="button" id="cert-btn" onclick="submitFinalScore()" class="opacity-0 transition-opacity duration-1000 bg-emerald-600 text-black px-10 py-4 rounded font-bold text-xl uppercase tracking-widest hover:bg-emerald-500 transition-colors shadow-[0_0_20px_rgba(16,185,129,0.6)]">
                <span data-en="View Certificate" data-ms="Papar Sijil">View Certificate</span>
            </button>
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

        // --- GOOGLE API TRANSLATOR ---
        window.translationCache = {};
        
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

        // 🔥 UPDATED LANGUAGE APPLIER (PRIORITIZES DATABASE TRANSLATION OVER API) 🔥
        async function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    // Safe injection that completely overwrites inner HTML
                    el.innerHTML = el.getAttribute(`data-${lang}`);
                }
            });

            if(questions.length > 0 && !document.getElementById('game-terminal').classList.contains('hidden')) {
                const q = questions[currentIndex];
                if (q) {
                    if (lang === 'en') {
                        uiQuestion.innerText = q.question;
                    } else {
                        // Check if you have hardcoded 'question_ms' in the database!
                        if (q.question_ms && q.question_ms.trim() !== '') {
                            uiQuestion.innerText = q.question_ms;
                        } else {
                            // Fallback to Google Translate if database column is missing/empty
                            uiQuestion.innerText = "Translating...";
                            q.question_ms = await translateGoogleAPI(q.question, 'ms');
                            uiQuestion.innerText = q.question_ms;
                        }
                    }
                }
            }
        }
        
        applyLanguage(currentLang); 

        if (themeBtn) {
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

        if (langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        // GLOBAL CLICK SOUND
        const clickSound = document.getElementById('ui-click-sound');
        document.querySelectorAll('button, a, img').forEach(el => {
            el.addEventListener('click', () => {
                if(clickSound) {
                    clickSound.currentTime = 0;
                    clickSound.play().catch(e => console.log(e));
                }
            });
        });

        // --- GAMEPLAY VARIABLES ---
        const questions = @json($questions);
        const settings = @json($settings);
        const previousScore = {{ \App\Models\GameProgress::where('user_id', auth()->id())->first()->total_score ?? 0 }};

        let globalTimeLeft = (settings?.total_minutes || 5) * 60;
        const maxQTime = settings?.seconds_per_question || 10;
        const streakTarget = settings?.streak_threshold_seconds || 6;
        const streakBonus = settings?.streak_bonus_percent || 20;
        const maxMistakes = settings?.max_mistakes || 3;

        let currentIndex = 0;
        let mistakes = 0;
        let score = 0;
        let currentStreak = 0;
        let qTimeLeft = maxQTime;
        
        let globalTimerInterval;
        let qTimerInterval;
        let isGameOver = false;
        
        let wrongAnswersLog = [];

        const uiQuestion = document.getElementById('ui-question');
        const uiGlobalTimer = document.getElementById('ui-global-timer');
        const uiQTimer = document.getElementById('ui-q-timer');
        const uiScore = document.getElementById('ui-score');
        const uiStreak = document.getElementById('ui-streak');
        const livesContainer = document.getElementById('lives-container');
        const terminalBox = document.getElementById('game-terminal');
        const briefingScreen = document.getElementById('briefing-screen');
        const mainGameWrapper = document.getElementById('main-game-wrapper');

        const countdownScreen = document.getElementById('countdown-screen');
        const countdownNumber = document.getElementById('countdown-number');
        const lensFlare = document.getElementById('lens-flare');
        const flashBangContainer = document.getElementById('flash-bang-container');
        const tutorialModal = document.getElementById('tutorial-modal');

        const epicAudio = document.getElementById('epic-audio');
        const correctAudio = document.getElementById('correct-audio');
        const wrongAudio = document.getElementById('wrong-audio');
        const hit1Audio = document.getElementById('hit1-audio');
        const hit2Audio = document.getElementById('hit2-audio');
        const explodeAudio = document.getElementById('explode-audio');

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        // --- START LOGIC WITH SYNCED COUNTDOWN ---
        function triggerEpicAnimation() {
            countdownNumber.classList.remove('epic-count');
            lensFlare.classList.remove('epic-flare');
            void countdownNumber.offsetWidth; 
            void lensFlare.offsetWidth;
            countdownNumber.classList.add('epic-count');
            lensFlare.classList.add('epic-flare');
        }

        let isStarting = false;
        function startCountdown(btnElement) {
            if (isStarting) return;
            isStarting = true;

            if (btnElement) {
                btnElement.disabled = true;
                btnElement.style.pointerEvents = 'none';
                btnElement.style.opacity = '0.5';
            }

            if (roomMusic.paused) {
                roomMusic.play().catch(e => console.log("Audio play blocked:", e));
            }

            // HIDE GAME CONTAINER TO FIX Z-INDEX OVERLAP
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
                        countdownNumber.classList.remove('text-red-500');
                        countdownNumber.classList.add('text-yellow-400');
                    }
                    
                    triggerEpicAnimation(); 
                    
                    step++;
                    setTimeout(runCountdownStep, 1000);
                } else {
                    // Trigger Flashbang
                    flashBangContainer.innerHTML = '<div class="white-flash"></div>';
                    countdownScreen.classList.add('hidden');
                    countdownScreen.classList.remove('flex');
                    
                    setTimeout(() => {
                        // Pop Tutorial Modal
                        tutorialModal.classList.remove('hidden');
                        tutorialModal.classList.add('flex');
                        setTimeout(() => tutorialModal.classList.remove('opacity-0'), 50);
                    }, 150);
                }
            }

            runCountdownStep();
        }

        // --- CLOSE TUTORIAL & START ACTUAL RAPID-FIRE GAME ---
        function closeTutorialAndStart() {
            const tutVideo = document.getElementById('tutorial-video');
            if(tutVideo) tutVideo.pause();
            
            tutorialModal.classList.add('opacity-0');
            
            setTimeout(() => {
                tutorialModal.classList.add('hidden');
                tutorialModal.classList.remove('flex');
                
                // RESTORE GAME CONTAINER
                mainGameWrapper.style.display = 'flex';
                
                briefingScreen.classList.add('hidden'); 
                briefingScreen.style.display = 'none';
                
                const hud = document.getElementById('hud-section');
                hud.classList.remove('hidden');
                terminalBox.classList.remove('hidden');
                
                setTimeout(() => {
                    hud.classList.remove('opacity-0');
                    terminalBox.classList.remove('opacity-0');
                }, 50);
                
                startGame();
            }, 500);
        }

        function startGame() {
            if(questions.length === 0) {
                uiQuestion.innerText = currentLang === 'en' ? "ERROR: NO QUESTIONS IN DATABANKS." : "RALAT: TIADA SOALAN DI PANGKALAN DATA.";
                document.getElementById('action-buttons').style.display = 'none';
                return;
            }
            
            applyLanguage(currentLang); 
            
            globalTimerInterval = setInterval(() => {
                globalTimeLeft--;
                uiGlobalTimer.innerText = `TIMER: ${formatTime(globalTimeLeft)}`;
                if(globalTimeLeft <= 0) forceDeath(currentLang === 'en' ? "GLOBAL TIME EXPIRED." : "MASA KESELURUHAN TAMAT.");
            }, 1000);

            loadQuestion();
        }

        async function loadQuestion() {
            if (currentIndex >= questions.length || currentIndex >= 30) { 
                triggerVictory();
                return;
            }

            const q = questions[currentIndex];
            
            if (currentLang === 'en') {
                uiQuestion.innerText = q.question;
            } else {
                // Check if you have hardcoded 'question_ms' in the database!
                if (q.question_ms && q.question_ms.trim() !== '') {
                    uiQuestion.innerText = q.question_ms;
                } else {
                    uiQuestion.innerText = "Translating...";
                    q.question_ms = await translateGoogleAPI(q.question, 'ms');
                    uiQuestion.innerText = q.question_ms;
                }
            }
            
            document.getElementById('progress-counter').innerText = `(${currentIndex + 1}/${Math.min(questions.length, 30)})`;

            qTimeLeft = maxQTime;
            uiQTimer.innerText = qTimeLeft.toFixed(1) + 's';
            uiQTimer.classList.replace('bg-red-900', 'bg-red-600');

            clearInterval(qTimerInterval);
            qTimerInterval = setInterval(() => {
                qTimeLeft -= 0.1;
                uiQTimer.innerText = Math.max(0, qTimeLeft).toFixed(1) + 's';
                
                if(qTimeLeft <= 3.0) uiQTimer.classList.replace('bg-red-600', 'bg-red-900'); 

                if(qTimeLeft <= 0) {
                    clearInterval(qTimerInterval);
                    if(wrongAudio) { wrongAudio.currentTime = 0; wrongAudio.play(); }
                    
                    let qText = q.question || "Unknown Question";
                    let shortQ = qText.length > 50 ? qText.substring(0, 50) + "..." : qText;
                    wrongAnswersLog.push(`Q${currentIndex + 1} ("${shortQ}"): Time expired (No answer)`);
                    
                    takeDamage(); 
                }
            }, 100);
        }

        let isSubmitting = false;

        function submitAnswer(playerChoice) {
            if (isSubmitting) return;
            isSubmitting = true;
            setTimeout(() => { isSubmitting = false; }, 400);

            clearInterval(qTimerInterval);
            const q = questions[currentIndex];
            const isActuallyTrue = q.is_true == 1;
            const playerGuessedTrue = playerChoice === 1;

            if (playerGuessedTrue === isActuallyTrue) {
                if(correctAudio) { correctAudio.currentTime = 0; correctAudio.play(); }

                let timeTaken = maxQTime - qTimeLeft;
                let earnedPoints = 100;

                if(timeTaken <= streakTarget) {
                    currentStreak++;
                    let multiplier = 1 + ((streakBonus * currentStreak) / 100);
                    earnedPoints = Math.floor(earnedPoints * multiplier);
                    
                    uiStreak.innerText = `STREAK x${multiplier.toFixed(1)}`;
                    uiStreak.classList.remove('hidden');
                } else {
                    currentStreak = 0;
                    uiStreak.classList.add('hidden');
                }

                score += earnedPoints;
                uiScore.innerText = `SCORE: ${score}`;
                
                currentIndex++;
                loadQuestion();
            } else {
                if(wrongAudio) { wrongAudio.currentTime = 0; wrongAudio.play(); }
                
                let guessedStatus = playerGuessedTrue ? "TRUE" : "FALSE";
                let actualStatus = isActuallyTrue ? "TRUE" : "FALSE";
                let qText = q.question || "Unknown Question";
                let shortQ = qText.length > 50 ? qText.substring(0, 50) + "..." : qText;
                wrongAnswersLog.push(`Q${currentIndex + 1} ("${shortQ}"): Guessed ${guessedStatus}, but was actually ${actualStatus}`);
                
                takeDamage();
            }
        }

        function takeDamage() {
            currentStreak = 0;
            uiStreak.classList.add('hidden');
            mistakes++;
            
            terminalBox.classList.remove('shake-damage');
            void terminalBox.offsetWidth;
            terminalBox.classList.add('shake-damage');
            
            const liveBars = document.querySelectorAll('.life-bar');
            if(mistakes <= maxMistakes) {
                const heart = liveBars[maxMistakes - mistakes];
                if (heart) {
                    heart.classList.remove('text-red-500', 'drop-shadow-[0_0_10px_rgba(239,68,68,0.8)]');
                    heart.classList.add('text-gray-700', 'opacity-50');
                }
            }

            if (mistakes >= maxMistakes) {
                forceDeath(currentLang === 'en' ? "TOO MANY ERRORS. MAINFRAME LOCKED." : "KESILAPAN MAKSIMUM DICAPAI. MAINFRAME DIKUNCI.");
            } else {
                currentIndex++;
                loadQuestion(); 
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

        function forceDeath(msg) {
            if(isGameOver) return;
            isGameOver = true;
            clearInterval(globalTimerInterval);
            clearInterval(qTimerInterval);
            
            let fadeRoomAudio = setInterval(function () {
                if (roomMusic.volume > 0.05) {
                    roomMusic.volume -= 0.05;
                } else {
                    roomMusic.pause();
                    clearInterval(fadeRoomAudio);
                }
            }, 100);
            
            uiQuestion.innerText = msg;
            uiQuestion.classList.add('text-red-600', 'animate-pulse');
            
            const btnText = currentLang === 'en' ? "RETRY MAINFRAME" : "CUBA SEMULA";
            document.getElementById('action-buttons').innerHTML = `
                <a href="javascript:location.reload()" class="w-full bg-red-900 hover:bg-red-700 text-white font-bold py-4 rounded uppercase tracking-widest text-xl">${btnText}</a>
            `;

            // 🔥 CALL THE SILENT LOGGER HERE SO IT LOGS IMMEDIATELY 🔥
            logRoomFailure(5);
        }

        let boxClicks = 0;
        const shieldBox = document.getElementById('shield-box');

        function triggerVictory() {
            isGameOver = true;
            clearInterval(globalTimerInterval);
            clearInterval(qTimerInterval);

            roomMusic.pause();
            
            const hud = document.getElementById('hud-section');
            hud.classList.add('transition-opacity', 'duration-1000', 'opacity-0');
            terminalBox.classList.add('transition-opacity', 'duration-1000', 'opacity-0');
            
            setTimeout(() => {
                hud.classList.add('hidden');
                terminalBox.classList.add('hidden');
                
                const boxScreen = document.getElementById('box-screen');
                boxScreen.classList.remove('hidden');
                boxScreen.classList.add('flex');
                
                setTimeout(() => {
                    boxScreen.classList.remove('opacity-0');
                }, 50);
            }, 1000);
        }

        shieldBox.addEventListener('click', () => {
            boxClicks++;
            
            if(boxClicks === 1) {
                if(hit1Audio) { hit1Audio.currentTime = 0; hit1Audio.play(); }
                shieldBox.classList.add('shake-1');
            }
            if(boxClicks === 2) {
                if(hit2Audio) { hit2Audio.currentTime = 0; hit2Audio.play(); }
                shieldBox.classList.remove('shake-1');
                shieldBox.classList.add('shake-2');
            }
            if(boxClicks === 3) {
                if(explodeAudio) { explodeAudio.currentTime = 0; explodeAudio.play(); }
                shieldBox.classList.remove('shake-2'); 
                document.getElementById('flash-bang-container').innerHTML = '<div class="white-flash"></div>';
                
                setTimeout(() => {
                    document.getElementById('box-screen').classList.add('hidden');
                    document.getElementById('box-screen').classList.remove('flex');
                    
                    const revealScreen = document.getElementById('reveal-screen');
                    revealScreen.classList.remove('hidden');
                    revealScreen.classList.add('flex');
                    revealScreen.classList.remove('opacity-0');
                    
                    const roomScoreDisplay = document.getElementById('room-score-display');
                    const finalScoreDisplay = document.getElementById('final-score-display');
                    
                    if (roomScoreDisplay && finalScoreDisplay) {
                        roomScoreDisplay.innerText = score;
                        finalScoreDisplay.innerText = score + previousScore; 
                    }

                    animateLetters();
                }, 500); 
            }
        });

        function animateLetters() {
            setTimeout(() => {
                const E = document.getElementById('letter-E');
                if(E) { E.classList.remove('opacity-0', 'scale-150'); E.classList.add('opacity-100', 'scale-100'); }
            }, 500);

            setTimeout(() => {
                const C = document.getElementById('letter-C');
                if(C) { C.classList.remove('opacity-0', 'scale-150'); C.classList.add('opacity-100', 'scale-100'); }
            }, 1000);

            setTimeout(() => {
                const K = document.getElementById('letter-K');
                if(K) { K.classList.remove('opacity-0', 'scale-150'); K.classList.add('opacity-100', 'scale-100'); }
            }, 1500);

            setTimeout(() => {
                const meaning = document.getElementById('meaning-text');
                if(meaning) meaning.classList.remove('opacity-0');
            }, 2500);

            setTimeout(() => {
                const scoreText = document.getElementById('score-text');
                if (scoreText) scoreText.classList.remove('opacity-0');
            }, 3500);

            setTimeout(() => {
                const certBtn = document.getElementById('cert-btn');
                if(certBtn) certBtn.classList.remove('opacity-0');
            }, 4500);
        }

        function submitFinalScore() {
            let correctAnswers = currentIndex - mistakes; 
            const encodedWrongs = encodeURIComponent(JSON.stringify(wrongAnswersLog));
            window.location.href = "{{ route('agent.level5.complete') }}?score=" + score + "&correct=" + correctAnswers + "&incorrect=" + mistakes + "&wrong_answers=" + encodedWrongs;
        }
    </script>
   
@include('partials.cursor')
</body>
</html>