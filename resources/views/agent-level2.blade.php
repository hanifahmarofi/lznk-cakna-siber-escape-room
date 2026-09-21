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
    <title>S.H.I.E.L.D // The Brute Force Gate</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;600;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            font-family: 'Share Tech Mono', monospace; 
            overflow-x: hidden; 
            overflow-y: auto;
            transition: background-color 0.3s ease;
        }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        
        :root {
            --bg-color: #020617; 
            --text-color: #d1d5db; 
            --terminal-bg: rgba(10, 15, 30, 0.95);
            --terminal-border: #3b82f6;
            --terminal-header: #1e3a8a;
            --terminal-shadow: 0 0 30px rgba(59, 130, 246, 0.15);
            
            --btn-bg: rgba(30, 58, 138, 0.2);
            --btn-border: rgba(59, 130, 246, 0.5);
            --btn-hover-bg: rgba(59, 130, 246, 0.4);
            --btn-hover-border: #60a5fa;
            --btn-text: #bfdbfe;
            
            --toggle-bg: rgba(59, 130, 246, 0.1);
            --toggle-text: #3b82f6;
            --toggle-hover-bg: rgba(59, 130, 246, 0.2);
            --toggle-border: rgba(59, 130, 246, 0.5);
            
            --question-text: #ffffff;
            --card-bg: rgba(21, 21, 21, 0.85); 
            --card-hover: rgba(31, 31, 31, 0.9);
            --title-color: #3b82f6;
            --value-color: #ffffff;

            /* 🔥 VIDEO BACKGROUND VARIABLES (DARK MODE BLUE) 🔥 */
            --vid-filter: hue-rotate(180deg) saturate(150%);
            --vid-overlay: rgba(30, 58, 138, 0.2);
        }
        
        .light-mode {
            --bg-color: #f8fafc; 
            --text-color: #0f172a;
            --terminal-bg: rgba(255, 255, 255, 0.95);
            --terminal-border: #2563eb; 
            --terminal-header: #93c5fd; 
            --terminal-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
            
            --btn-bg: rgba(239, 246, 255, 0.8); 
            --btn-border: #60a5fa; 
            --btn-hover-bg: #dbeafe; 
            --btn-hover-border: #3b82f6; 
            --btn-text: #1e40af; 
            
            --toggle-bg: rgba(255, 255, 255, 0.9);
            --toggle-text: #0f172a;
            --toggle-hover-bg: #e2e8f0;
            --toggle-border: #94a3b8;
            
            --question-text: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.95); 
            --card-hover: rgba(243, 244, 246, 0.98);
            --title-color: #2563eb;
            --value-color: #0f172a;

            /* 🔥 VIDEO BACKGROUND VARIABLES (LIGHT MODE BLUE INVERT) 🔥 */
            --vid-filter: invert(1) brightness(1.2) saturate(150%);
            --vid-overlay: rgba(255, 255, 255, 0.5);
            
        }

        /* 🔥 THEME VIDEO CLASSES 🔥 */
        .theme-video { filter: var(--vid-filter); transition: filter 0.5s ease; }
        .theme-vid-overlay { background-color: var(--vid-overlay); transition: background-color 0.5s ease; }

        /* 🚨 IMMUNE CLASSES FOR LIGHT MODE 🚨 */
        .always-white { color: #ffffff !important; }
        #fail-return-btn, #fail-return-btn span { color: #ffffff !important; }

        .light-mode .text-gray-300, .light-mode .text-gray-400 { color: #334155 !important; }
        .light-mode .text-white { color: #0f172a !important; }
        .light-mode .text-blue-500 { color: #1d4ed8 !important; } 
        .light-mode .drop-shadow-\[0_0_15px_rgba\(59\,130\,246\,0\.8\)\] { filter: drop-shadow(0 0 5px rgba(37, 99, 235, 0.4)); }
        /* 🚨 HUD BOX LIGHT MODE OVERRIDES 🚨 */
        .light-mode .bg-black\/40 { background-color: rgba(255, 255, 255, 0.9) !important; }
        .light-mode .border-blue-900\/50 { border-color: rgba(0, 0, 0, 0.15) !important; }

        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; pointer-events: none; }

        .game-container { max-width: 1000px; margin: 0 auto; min-height: 100vh; padding-top: 6rem; padding-bottom: 2rem; display: flex; flex-direction: column; justify-content: center; }
        
        .terminal-box { background: var(--terminal-bg); border: 1px solid var(--terminal-border); box-shadow: var(--terminal-shadow); border-radius: 8px; overflow: hidden; position: relative; transition: all 0.3s ease;}
        .terminal-header { background: var(--terminal-header); padding: 16px 24px; border-bottom: 1px solid var(--terminal-border); display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease;}
        
        #ui-question { color: var(--question-text); transition: color 0.3s ease; }
        
        .option-btn { background: var(--btn-bg); border: 1px solid var(--btn-border); color: var(--btn-text); transition: all 0.2s; text-align: left; cursor: pointer; pointer-events: auto; }
        .option-btn:hover { background: var(--btn-hover-bg); border-color: var(--btn-hover-border); padding-left: 1.5rem; }
        
        .theme-btn { background: var(--toggle-bg); color: var(--toggle-text); border: 1px solid var(--toggle-border); transition: all 0.3s ease; }
        .theme-btn:hover { background: var(--toggle-hover-bg); }
        
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

        .epic-bg {
            background: radial-gradient(circle at center, #1e3a8a 0%, #0f172a 60%, #000000 100%);
        }
        
        .epic-text {
            font-family: 'Anton', sans-serif;
            background: linear-gradient(180deg, #eff6ff 0%, #3b82f6 50%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0px 0px 15px rgba(59, 130, 246, 0.8));
        }

        .epic-count { animation: epicZoom 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes epicZoom {
            0% { transform: scale(1.8); opacity: 0; filter: brightness(2) drop-shadow(0 0 50px rgba(255,255,255,1)); }
            20% { opacity: 1; filter: brightness(1.5) drop-shadow(0 0 30px rgba(59, 130, 246, 0.8)); }
            100% { transform: scale(1); opacity: 1; filter: brightness(1) drop-shadow(0 0 10px rgba(59, 130, 246, 0.5)); }
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

    <div id="google_translate_element" class="hidden"></div>

    <audio id="room-music" src="{{ asset('audio/Battlefield.mp3') }}" loop preload="auto" crossorigin="anonymous" class="hidden"></audio>
    <audio id="epic-audio" preload="auto" class="hidden"><source src="{{ asset('audio/countdown-epic.mp3') }}" type="audio/mpeg"></audio>
    <audio id="correct-audio" preload="auto" class="hidden"><source src="{{ asset('audio/right-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="wrong-audio" preload="auto" class="hidden"><source src="{{ asset('audio/wrong-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto" class="hidden"></audio>

    <div class="fixed top-4 right-4 z-[9999999] flex gap-3 sc-anim sc-in-right d-5 pointer-events-auto">
        <button id="lang-toggle" class="theme-btn backdrop-blur px-3 py-1.5 rounded-full text-xs font-bold tracking-wider shadow-[0_0_10px_rgba(59,130,246,0.2)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-btn backdrop-blur px-3 py-1.5 rounded-full text-xs font-bold tracking-wider shadow-[0_0_10px_rgba(59,130,246,0.2)]">
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

    <div id="countdown-screen" class="hidden fixed inset-0 z-[99990] items-center justify-center epic-bg overflow-hidden pointer-events-none">
        <div class="particles"></div>
        <div id="lens-flare" class="absolute w-[200%] h-2 bg-white shadow-[0_0_60px_20px_#60a5fa] opacity-0 -rotate-12 z-20 mix-blend-screen pointer-events-none"></div>
        <div id="countdown-number" class="relative z-10 text-[18rem] md:text-[25rem] epic-text pointer-events-none opacity-0">3</div>
    </div>

    <div id="tutorial-modal" class="hidden fixed inset-0 z-[99900] flex-col items-center justify-center p-4 transition-opacity duration-500 opacity-0 bg-black/90 backdrop-blur-md">
        <div class="theme-card border border-blue-500/50 max-w-4xl w-full max-h-[95vh] overflow-y-auto p-6 md:p-8 rounded-xl shadow-[0_0_40px_rgba(59,130,246,0.3)] flex flex-col pointer-events-auto">
            
            <h2 class="theme-title text-2xl font-bold uppercase tracking-widest mb-4 flex items-center gap-3 shrink-0">
                <svg class="w-6 h-6 animate-pulse text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="translation-target" data-en="Pre-Mission Guidance" data-ms="Panduan Pra-Misi">Panduan Pra-Misi</span>
            </h2>
            
            <div class="relative w-full bg-black border border-gray-700 rounded-lg mb-6 overflow-hidden flex justify-center shadow-inner shrink-0" style="padding-bottom: 56.25%;">
                <video id="tutorial-video" controls class="absolute top-0 left-0 w-full h-full object-contain">
                    <source src="{{ asset('video/tutorial2.mp4') }}" type="video/mp4">
                </video>
            </div>
            
            <div class="bg-blue-500/10 border-l-4 border-blue-500 p-4 mb-6 rounded text-left shrink-0">
                <p class="theme-value font-mono text-sm leading-relaxed"    
                 data-ms="Sila teliti video panduan di atas. Beri perhatian penuh kepada teknik serangan Brute Force dan cara mengesan konfigurasi kata laluan yang terdedah. Setelah bersedia, tutup tetingkap ini untuk memulakan analisis."
                 data-en="Please watch the video guide above. Pay full attention to Brute Force attack techniques and how to detect vulnerable password configurations. Once ready, close this window to start the analysis.">
                 Sila teliti video panduan di atas. Beri perhatian penuh kepada teknik serangan Brute Force dan cara mengesan konfigurasi kata laluan yang terdedah. Setelah bersedia, tutup tetingkap ini untuk memulakan analisis.
                </p>
            </div>
            
            <button onclick="closeTutorialAndStart()" class="relative z-50 w-full bg-blue-600 hover:bg-blue-500 text-black font-bold py-3 rounded uppercase tracking-widest shadow-[0_0_15px_rgba(59,130,246,0.4)] transition-all pointer-events-auto shrink-0">
                <span class="translation-target" data-en="Close & Begin Analysis" data-ms="Tutup & Mulakan Analisis">Tutup & Mulakan Analisis</span>
            </button>
        </div>
    </div>

    <div class="game-container relative z-50" id="main-game-wrapper">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 px-2 gap-4 sc-anim sc-in-left">
            <div>
                <h1 class="title-font text-3xl text-blue-500 tracking-widest uppercase mb-1 drop-shadow-[0_0_10px_rgba(59,130,246,0.8)]">S.H.I.E.L.D. DECRYPTION ENGINE</h1>
                <p class="text-blue-700 font-bold uppercase tracking-widest text-xs" data-en="Mission 02: The Brute Force Gate" data-ms="Misi 02: Pintu Brute Force">Mission 02: The Brute Force Gate</p>
            </div>
            
            <div class="flex items-center gap-3 bg-black/40 border border-blue-900/50 px-4 py-2 rounded-lg backdrop-blur-sm self-start md:self-auto">
                <div class="text-gray-400 text-[10px] uppercase tracking-widest font-bold whitespace-nowrap" data-en="Decryption Integrity (Mistakes Allowed: 3)" data-ms="Integriti Nyahsulit (Kesilapan Dibenarkan: 3)">
                    Decryption Integrity <span class="hidden sm:inline">(Mistakes Allowed: 3)</span>
                </div>
                <div class="flex gap-1" id="lives-container">
                    <svg class="w-5 h-5 text-blue-500 drop-shadow-[0_0_10px_rgba(59,130,246,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-5 h-5 text-blue-500 drop-shadow-[0_0_10px_rgba(59,130,246,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg class="w-5 h-5 text-blue-500 drop-shadow-[0_0_10px_rgba(59,130,246,0.8)] transition-all life-bar" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
        </div>

        <div id="briefing-screen" class="terminal-box flex flex-col h-[600px] justify-center items-center p-8 text-center bg-black sc-anim sc-in-right tv-turn-on">
            <h2 class="title-font text-4xl mb-4 uppercase tracking-wider text-blue-500 drop-shadow-[0_0_15px_rgba(59,130,246,0.8)]" data-en="MISSION BRIEFING" data-ms="TAKLIMAT MISI">MISSION BRIEFING</h2>
            
            <div class="max-w-2xl text-gray-300 space-y-6 mb-10 text-base md:text-lg font-bold leading-relaxed">
                <p data-en="Welcome to <span class='text-blue-400 font-extrabold'>The Brute Force Gate</span>. Attackers are constantly looking for weak passwords to infiltrate the network." data-ms="Selamat datang ke <span class='text-blue-400 font-extrabold'>Pintu Brute Force</span>. Penyerang sentiasa mencari kata laluan yang lemah untuk menceroboh rangkaian.">
                    Welcome to <span class="text-blue-400 font-extrabold">The Brute Force Gate</span>. Attackers are constantly looking for weak passwords to infiltrate the network.
                </p>
                <p data-en="Your objective is to identify the strongest, most secure password configurations or recognize critical vulnerabilities before the gate fails." data-ms="Objektif anda adalah untuk mengenal pasti konfigurasi kata laluan yang paling kukuh dan selamat atau mengenali kelemahan kritikal sebelum pintu gagal.">
                    Your objective is to identify the strongest, most secure password configurations or recognize critical vulnerabilities before the gate fails.
                </p>
                <p class="text-red-500 font-extrabold" data-en="WARNING: You are only permitted 3 mistakes before the system locks you out." data-ms="AMARAN: Anda hanya dibenarkan 3 kesilapan sebelum sistem mengunci anda.">
                    WARNING: You are only permitted 3 mistakes before the system locks you out.
                </p>
                <p class="italic text-blue-600 uppercase tracking-widest mt-4 font-extrabold" data-en="Good luck, Agent." data-ms="Semoga berjaya, Ejen.">Good luck, Agent.</p>
            </div>

            <button id="launch-btn" class="relative z-50 bg-blue-600 text-black px-10 py-4 rounded font-bold text-xl uppercase tracking-widest hover:bg-blue-500 transition-colors shadow-[0_0_20px_rgba(59,130,246,0.6)] cursor-pointer pointer-events-auto">
                <span data-en="Launch Mission" data-ms="Mulakan Misi">Launch Mission</span>
            </button>
        </div>

        <div id="game-terminal" class="terminal-box flex flex-col h-[600px] hidden">
            <div class="terminal-header">
                <div class="text-blue-300 font-extrabold tracking-widest uppercase text-sm md:text-base flex items-center gap-3">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(250,204,21,0.8)]"></span>
                    <span data-en="Cracking Sector" data-ms="Sektor Pemecahan">Cracking Sector</span> <span id="progress-counter">(1/?)</span>
                </div>
                <div id="ui-category" class="text-white bg-blue-900/50 px-3 py-1 rounded text-xs uppercase tracking-widest border border-blue-400/50 dynamic-translation font-bold" data-original="Category">Category</div>
            </div>

            <div class="flex-1 p-8 flex flex-col justify-center" id="qa-display">
                <div class="text-2xl md:text-3xl leading-relaxed mb-10 border-l-4 border-blue-500 pl-6 dynamic-translation font-bold" id="ui-question" data-original="Initializing Brute Force Protocol...">
                    Initializing Brute Force Protocol...
                </div>
                
                <div class="grid grid-cols-1 gap-4" id="options-container">
                    <button class="option-btn p-5 rounded font-bold text-base tracking-wide flex items-start gap-3" onclick="submitAnswer(1)">
                        <span class="text-blue-500 shrink-0 mt-0.5">[ 1 ]</span>
                        <span id="ui-opt1" class="dynamic-translation leading-relaxed" data-original="Option 1">Option 1</span>
                    </button>
                    <button class="option-btn p-5 rounded font-bold text-base tracking-wide flex items-start gap-3" onclick="submitAnswer(2)">
                        <span class="text-blue-500 shrink-0 mt-0.5">[ 2 ]</span>
                        <span id="ui-opt2" class="dynamic-translation leading-relaxed" data-original="Option 2">Option 2</span>
                    </button>
                    <button class="option-btn p-5 rounded font-bold text-base tracking-wide flex items-start gap-3" onclick="submitAnswer(3)">
                        <span class="text-blue-500 shrink-0 mt-0.5">[ 3 ]</span>
                        <span id="ui-opt3" class="dynamic-translation leading-relaxed" data-original="Option 3">Option 3</span>
                    </button>
                    <button class="option-btn p-5 rounded font-bold text-base tracking-wide flex items-start gap-3" onclick="submitAnswer(4)">
                        <span class="text-blue-500 shrink-0 mt-0.5">[ 4 ]</span>
                        <span id="ui-opt4" class="dynamic-translation leading-relaxed" data-original="Option 4">Option 4</span>
                    </button>
                </div>
            </div>
        </div>

        <div id="end-screen" class="terminal-box h-[600px] hidden flex-col items-center justify-center text-center p-8 bg-black/90">
            <h2 id="end-title" class="title-font text-5xl mb-4 uppercase tracking-wider text-blue-500 drop-shadow-[0_0_15px_rgba(59,130,246,0.8)]" data-en="GATE BREACHED" data-ms="PINTU DITEMBUSI">GATE BREACHED</h2>
            <p id="end-msg" class="text-gray-400 mb-8 max-w-md mx-auto leading-relaxed" data-en="Mission complete. You have earned the second fragment of the master password." data-ms="Misi Selesai. Anda telah memperoleh serpihan kedua kata laluan utama.">Mission complete. You have earned the second fragment of the master password.</p>
            
            <div id="frag-box" class="w-24 h-24 border-2 border-blue-500 flex items-center justify-center text-5xl font-bold text-white shadow-[0_0_20px_rgba(59,130,246,0.5)] mb-8 bg-blue-900/30">
                E
            </div>

            <div class="flex flex-col gap-4">
                <a id="end-btn" href="{{ route('agent.level2.complete') }}" class="bg-blue-600 text-black px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-blue-500 transition-colors shadow-[0_0_15px_rgba(59,130,246,0.4)] pointer-events-auto">
                    <span data-en="Return to Hub" data-ms="Kembali ke Hab">Return to Hub</span>
                </a>
                
                <a id="fail-return-btn" href="{{ route('agent.mission') }}" class="hidden bg-gray-800 px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-gray-700 transition-colors border border-gray-600 pointer-events-auto" style="color: #ffffff !important;">
                    <span data-en="Return to Mission Control" data-ms="Kembali ke Papan Misi" style="color: #ffffff !important;">Return to Mission Control</span>
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
            
            setTimeout(() => { document.body.classList.add('loaded'); }, 50); 

            // 🔥 EXPLICIT BIND FOR LAUNCH BUTTON 🔥
            const launchBtn = document.getElementById('launch-btn');
            if(launchBtn) {
                launchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    startCountdown(this);
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

        // --- Global Dictionary Cache for Translations ---
        window.translationCache = {};

        // --- GOOGLE API TWO-WAY AUTO-TRANSLATOR ---
        async function translateGoogleAPI(text, targetLang) {
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                
                let fullText = "";
                if (data && data[0]) {
                    for (let i = 0; i < data[0].length; i++) {
                        if (data[0][i][0]) {
                            fullText += data[0][i][0];
                        }
                    }
                }
                return fullText || text; 
            } catch(e) {
                console.error("Translation Failed:", e);
                return text; 
            }
        }

        async function processDynamicTranslations(lang) {
            const dynamicElements = document.querySelectorAll('.dynamic-translation');
            
            for(let el of dynamicElements) {
                let originalText = el.getAttribute('data-original');
                
                if(!originalText || originalText === '--') continue;

                let cacheKey = `${lang}_${originalText}`;
                
                if(window.translationCache[cacheKey]) {
                    el.innerText = window.translationCache[cacheKey];
                } else {
                    if(originalText !== "Initializing Brute Force Protocol..." && originalText !== "Option 1" && originalText !== "Option 2" && originalText !== "Option 3" && originalText !== "Option 4" && originalText !== "Category") {
                        el.innerText = "Translating..."; 
                    }
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

        if (currentTheme === 'light') { bodyEl.classList.add('light-mode'); if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    el.innerHTML = el.getAttribute(`data-${lang}`);
                }
            });
            
            if(document.getElementById('ui-question') && document.getElementById('ui-question').getAttribute('data-original') !== "Initializing Brute Force Protocol...") {
                processDynamicTranslations(lang);
            }
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

        // CLICK AUDIO LISTENER
        const clickSound = document.getElementById('ui-click-sound');
        document.querySelectorAll('button, a').forEach(el => {
            el.addEventListener('click', () => {
                if(clickSound) {
                    clickSound.currentTime = 0;
                    clickSound.play().catch(e => console.log(e));
                }
            });
        });

        // --- GAME LOGIC ---
        const questions = @json($questions);
        
        let currentIndex = 0;
        let mistakes = 0;
        const MAX_MISTAKES = 3;

        let wrongAnswersLog = [];

        const uiCategory = document.getElementById('ui-category');
        const uiQuestion = document.getElementById('ui-question');
        const uiOpt1 = document.getElementById('ui-opt1');
        const uiOpt2 = document.getElementById('ui-opt2');
        const uiOpt3 = document.getElementById('ui-opt3');
        const uiOpt4 = document.getElementById('ui-opt4');
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
                        countdownNumber.classList.remove('text-blue-500');
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
                
                if(questions.length > 0) {
                    loadQuestion();
                } else {
                    uiQuestion.innerText = currentLang === 'ms' ? "RALAT SISTEM: TIADA PROTOKOL OBJEKTIF DIJUMPAI DALAM PANGKALAN DATA." : "SYSTEM ERROR: NO OBJECTIVE PROTOCOLS FOUND IN DATABASE.";
                    uiQuestion.classList.add('glitch-text', 'font-mono');
                    document.getElementById('options-container').style.display = 'none';
                }
            }, 500);
        }

        function loadQuestion() {
            if (currentIndex >= questions.length) {
                endGame(true);
                return;
            }

            const q = questions[currentIndex];
            
            const displayArea = document.getElementById('qa-display');
            displayArea.classList.remove('fade-in');
            void displayArea.offsetWidth;
            displayArea.classList.add('fade-in');

            uiCategory.setAttribute('data-original', q.category || 'General Data');
            uiQuestion.setAttribute('data-original', q.question);
            uiOpt1.setAttribute('data-original', q.option_1);
            uiOpt2.setAttribute('data-original', q.option_2);
            uiOpt3.setAttribute('data-original', q.option_3);
            uiOpt4.setAttribute('data-original', q.option_4);
            
            progressCounter.innerText = `(${currentIndex + 1}/${questions.length})`;
            
            processDynamicTranslations(currentLang);
        }

        let isSubmitting = false;

        function submitAnswer(selectedOption) {
            if (isSubmitting) return;
            isSubmitting = true;

            setTimeout(() => { isSubmitting = false; }, 1500);

            const currentQ = questions[currentIndex];
            const isCorrect = parseInt(selectedOption) === parseInt(currentQ.correct_answer);

            if (isCorrect) {
                if(correctAudio) {
                    correctAudio.currentTime = 0;
                    correctAudio.play();
                }
                
                currentIndex++;
                loadQuestion();
            } else {
                if(wrongAudio) {
                    wrongAudio.currentTime = 0;
                    wrongAudio.play();
                }
                
                let chosenText = currentQ['option_' + selectedOption] || ("Option " + selectedOption);
                let questionText = currentQ.question || "Unknown Question";
                wrongAnswersLog.push(`Q${currentIndex + 1} ("${questionText}"): Chose incorrect option - "${chosenText}"`);
                
                takeDamage();
            }
        }

        function takeDamage() {
            mistakes++;
            
            terminalBox.classList.remove('shake');
            void terminalBox.offsetWidth;
            terminalBox.classList.add('shake');
            
            if(mistakes <= MAX_MISTAKES) {
                const heart = lives[MAX_MISTAKES - mistakes];
                if (heart) {
                    heart.classList.remove('text-blue-500', 'drop-shadow-[0_0_10px_rgba(59,130,246,0.8)]');
                    heart.classList.add('text-gray-700', 'opacity-50');
                }
            }

            if (mistakes >= MAX_MISTAKES) {
                setTimeout(() => { endGame(false); }, 500); 
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
                endTitle.classList.replace('text-blue-500', 'text-red-500');
                endTitle.classList.replace('drop-shadow-[0_0_15px_rgba(59,130,246,0.8)]', 'drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]');
                
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
                
                endBtn.classList.replace('bg-blue-600', 'bg-red-600');
                endBtn.classList.replace('hover:bg-blue-500', 'hover:bg-red-500');
                endBtn.classList.replace('shadow-[0_0_15px_rgba(59,130,246,0.4)]', 'shadow-[0_0_15px_rgba(239,68,68,0.4)]');
                
                const failReturnBtn = document.getElementById('fail-return-btn');
                if (failReturnBtn) {
                    failReturnBtn.classList.remove('hidden');
                    failReturnBtn.classList.add('inline-block');
                }
                
                endScreen.style.borderColor = "#ef4444";
                
                // 🔥 CALL THE SILENT LOGGER HERE SO IT LOGS IMMEDIATELY 🔥
                logRoomFailure(2);
                
            } else {
                let totalQuestions = questions.length; 
                let correctAnswers = totalQuestions - mistakes; 
                let finalScore = correctAnswers * 100; 
                
                const encodedWrongs = encodeURIComponent(JSON.stringify(wrongAnswersLog));
                
                let proceedBtn = document.getElementById('end-btn');
                if(!proceedBtn) proceedBtn = endScreen.querySelector('a');
                
                proceedBtn.setAttribute('href', `{{ route('agent.level2.complete') }}?score=${finalScore}&correct=${correctAnswers}&incorrect=${mistakes}&wrong_answers=${encodedWrongs}`);
            }
            
            applyLanguage(currentLang);
        }
    </script>
    
@include('partials.cursor')
</body>
</html>