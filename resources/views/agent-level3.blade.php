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
    <title>S.H.I.E.L.D // The Human Firewall</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
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
        .ui-font { font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; } 
        
        :root {
            --bg-color: #0f0500; 
            --text-color: #d1d5db; 
            --terminal-bg: rgba(20, 10, 0, 0.95);
            --terminal-border: #f59e0b;
            --terminal-header: #78350f;
            --terminal-shadow: 0 0 30px rgba(245, 158, 11, 0.15);
            
            --btn-bg: rgba(245, 158, 11, 0.1);
            --btn-text: #f59e0b;
            --btn-hover-bg: rgba(245, 158, 11, 0.2);
            --btn-border: rgba(245, 158, 11, 0.5);
            
            --card-bg: rgba(20, 10, 0, 0.95);
            --value-text: #d1d5db;
            --title-color: #f59e0b;

            /* 🔥 VIDEO BACKGROUND VARIABLES (DARK MODE AMBER) 🔥 */
            --vid-filter: none;
            --vid-overlay: rgba(180, 83, 9, 0.2);
        }
        
        .light-mode {
            --bg-color: #f8fafc; 
            --text-color: #0f172a;
            --terminal-bg: rgba(255, 255, 255, 0.95);
            --terminal-border: #d97706; 
            --terminal-header: #fcd34d; 
            --terminal-shadow: 0 10px 25px rgba(217, 119, 6, 0.2);
            
            --btn-bg: rgba(255, 255, 255, 0.9);
            --btn-text: #0f172a;
            --btn-hover-bg: #e2e8f0;
            --btn-border: #94a3b8;
            
            --card-bg: rgba(255, 255, 255, 0.95);
            --value-text: #1f2937;
            --title-color: #d97706;

            /* 🔥 VIDEO BACKGROUND VARIABLES (LIGHT MODE INVERT) 🔥 */
            --vid-filter: invert(1) hue-rotate(180deg) brightness(1.5);
            --vid-overlay: rgba(255, 255, 255, 0.5);
        }
        
        /* 🔥 THEME VIDEO CLASSES 🔥 */
        .theme-video { filter: var(--vid-filter); transition: filter 0.5s ease; }
        .theme-vid-overlay { background-color: var(--vid-overlay); transition: background-color 0.5s ease; }

        /* 🚨 IMMUNE CLASSES FOR LIGHT MODE 🚨 */
        .always-white { color: #ffffff !important; }
        .always-black { color: #000000 !important; }
        #fail-return-btn, #fail-return-btn span { color: #ffffff !important; }

        .light-mode .text-gray-300, .light-mode .text-gray-400 { color: #334155 !important; }
        .light-mode .text-emerald-500 { color: #047857 !important; }
        .light-mode .text-emerald-400 { color: #059669 !important; } 
        .light-mode .text-emerald-300 { color: #065f46 !important; }
        .light-mode .drop-shadow-\[0_0_10px_rgba\(245\,158\,11\,0\.8\)\] { filter: drop-shadow(0 0 5px rgba(217, 119, 6, 0.4)); }
        .light-mode .bg-black\/40 { background-color: rgba(255, 255, 255, 0.9) !important; }
        .light-mode .border-blue-900\/50 { border-color: rgba(0, 0, 0, 0.15) !important; }
        
        .theme-value { color: var(--value-text); transition: color 0.3s ease; }
        .theme-title { color: var(--title-color) !important; transition: color 0.3s ease; }

        /* 🚨 LIGHT MODE STATUS BADGE FIXES 🚨 */
        .light-mode .feedback-badge-fail { background-color: #ef4444 !important; color: #ffffff !important; border-color: #991b1b !important; }
        .light-mode .feedback-badge-success { background-color: #10b981 !important; color: #ffffff !important; border-color: #064e3b !important; }

        .game-container { max-width: 950px; margin: 0 auto; min-height: 100vh; padding-top: 6rem; padding-bottom: 2rem; display: flex; flex-direction: column; justify-content: center; }
        
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; }
        .terminal-box { background: var(--terminal-bg); border: 1px solid var(--terminal-border); box-shadow: var(--terminal-shadow); border-radius: 8px; overflow: hidden; position: relative; transition: all 0.3s ease;}
        .terminal-header { background: var(--terminal-header); padding: 16px 24px; border-bottom: 1px solid var(--terminal-border); display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease;}
        
        .theme-btn { background: var(--btn-bg); color: var(--btn-text); border: 1px solid var(--btn-border); transition: all 0.3s ease; }
        .theme-btn:hover { background: var(--btn-hover-bg); }

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

        .epic-bg { background: radial-gradient(circle at center, #92400e 0%, #451a03 60%, #000000 100%); }
        .epic-text {
            font-family: 'Anton', sans-serif;
            background: linear-gradient(180deg, #fef3c7 0%, #f59e0b 50%, #92400e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0px 0px 15px rgba(245, 158, 11, 0.8));
        }

        .epic-count { animation: epicZoom 1s cubic-bezier(0.1, 0.8, 0.3, 1) forwards; }
        @keyframes epicZoom {
            0% { transform: scale(1.8); opacity: 0; filter: brightness(2) drop-shadow(0 0 50px rgba(255,255,255,1)); }
            20% { opacity: 1; filter: brightness(1.5) drop-shadow(0 0 30px rgba(245, 158, 11, 0.8)); }
            100% { transform: scale(1); opacity: 1; filter: brightness(1) drop-shadow(0 0 10px rgba(245, 158, 11, 0.5)); }
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
        @keyframes moveParticles { 0% { transform: translateY(0) scale(1); } 100% { transform: translateY(-100px) scale(1.2); } }

        .white-flash { position: fixed; inset: 0; background: white; z-index: 99999; animation: flashBang 0.8s forwards; pointer-events: none; }
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

        .btn-blink { animation: slowBlink 2.5s ease-in-out infinite; }
        .btn-blink:hover { animation: none; opacity: 1; filter: brightness(1); }
        @keyframes slowBlink { 0%, 100% { opacity: 1; filter: brightness(1); } 50% { opacity: 0.5; filter: brightness(0.8); } }

        .zoom-in { animation: popZoom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        @keyframes popZoom { 0% { opacity: 0; transform: scale(0.7); } 100% { opacity: 1; transform: scale(1); } }
        
        /* 🚨 RIGID PHONE MOCKUP CSS 🚨 */
        .mockup-phone {
            width: 320px;
            min-width: 320px;
            height: 480px;
            min-height: 480px;
            border: 8px solid #1a1a1a;
            border-radius: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            background-color: #000;
            z-index: 20;
        }

        /* 🚨 PURE CSS FLOATING ZOOM BUTTON 🚨 */
        .custom-enlarge-btn {
            position: absolute;
            right: -75px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #d97706; /* amber-600 */
            color: #000000 !important;
            padding: 12px;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.6);
            border: 2px solid #78350f; /* amber-900 */
            z-index: 30;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .custom-enlarge-btn:hover {
            transform: translateY(-50%) scale(1.15);
            background-color: #fbbf24; /* amber-400 */
        }

        /* 🚨 PURE CSS ZOOM BACKDROP & CLOSE BUTTON 🚨 */
        .custom-zoom-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 9000 !important;
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .custom-zoom-backdrop.active {
            display: block;
            opacity: 1;
        }
        
        .custom-close-btn {
            position: fixed !important;
            top: 30px !important;
            right: 30px !important;
            z-index: 10001 !important;
            background-color: #dc2626 !important; 
            color: #ffffff !important;
            width: 60px;
            height: 60px;
            border-radius: 50% !important;
            border: 2px solid #fca5a5 !important;
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.8) !important;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }
        .custom-close-btn.active { display: flex; }
        .custom-close-btn:hover { transform: scale(1.15); background-color: #ef4444 !important; }

        /* 🚨 UN-TRAPPABLE LANDSCAPE ZOOM ANIMATION 🚨 */
        .phone-zoomed-state {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            z-index: 10000 !important; 
            animation: expandToLandscape 0.5s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
        }

        .phone-shrinking-state {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            z-index: 10000 !important;
            animation: shrinkToPortrait 0.4s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
        }

        @keyframes expandToLandscape {
            0% { width: 320px; height: 480px; box-shadow: 0 0 20px rgba(0,0,0,0.5); border-radius: 2.5rem; }
            100% { width: 85vw; max-width: 1000px; height: 75vh; max-height: 550px; border-radius: 1.5rem; box-shadow: 0 0 80px rgba(0,0,0,0.9); }
        }

        @keyframes shrinkToPortrait {
            0% { width: 85vw; max-width: 1000px; height: 75vh; max-height: 550px; border-radius: 1.5rem; box-shadow: 0 0 80px rgba(0,0,0,0.9); }
            100% { width: 320px; height: 480px; border-radius: 2.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); }
        }
        
        @media (max-width: 768px) {
            .custom-enlarge-btn {
                right: -20px;
                bottom: -20px;
                top: auto;
                transform: none;
            }
            .custom-enlarge-btn:hover { transform: scale(1.15); }
            
            @keyframes expandToLandscape {
                0% { width: 320px; height: 480px; border-radius: 2.5rem; }
                100% { width: 95vw; height: 85vh; border-radius: 1rem; }
            }
            @keyframes shrinkToPortrait {
                0% { width: 95vw; height: 85vh; border-radius: 1rem; }
                100% { width: 320px; height: 480px; border-radius: 2.5rem; }
            }
        }

        .phone-zoomed-state .mockup-notch, .phone-shrinking-state .mockup-notch {
            top: 50% !important;
            left: 0 !important;
            transform: translateY(-50%) !important;
            width: 28px !important;
            height: 140px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        /* Enlarge Chat UI for Landscape */
        .phone-zoomed-state #phone-header, .phone-shrinking-state #phone-header { padding-left: 3.5rem !important; padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
        .phone-zoomed-state #ui-sender, .phone-shrinking-state #ui-sender { font-size: 1.5rem !important; }
        .phone-zoomed-state #ui-platform-badge, .phone-shrinking-state #ui-platform-badge { font-size: 0.85rem !important; padding: 6px 12px !important; }
        
        .phone-zoomed-state #chat-bubble, .phone-shrinking-state #chat-bubble { max-width: 90% !important; padding: 2rem !important; border-radius: 1.5rem !important; }
        .phone-zoomed-state #chat-bubble.bubble-whatsapp, .phone-shrinking-state #chat-bubble.bubble-whatsapp { border-top-left-radius: 0 !important; }
        .phone-zoomed-state #chat-bubble.bubble-telegram, .phone-shrinking-state #chat-bubble.bubble-telegram { border-bottom-left-radius: 0 !important; }
        .phone-zoomed-state #chat-bubble.bubble-sms, .phone-shrinking-state #chat-bubble.bubble-sms { border-bottom-right-radius: 0 !important; }
        .phone-zoomed-state #ui-chat-message, .phone-shrinking-state #ui-chat-message { font-size: 1.5rem !important; line-height: 1.7 !important; }
        .phone-zoomed-state #ui-time, .phone-shrinking-state #ui-time { font-size: 0.9rem !important; bottom: 0.75rem !important; right: 1.25rem !important; }
        
        /* LANDSCAPE CALL LAYOUT */
        .phone-zoomed-state #call-layout, .phone-shrinking-state #call-layout { flex-direction: row !important; gap: 2rem !important; padding: 1.5rem 2rem !important; align-items: stretch !important; }
        .phone-zoomed-state #call-layout > div.flex-col, .phone-shrinking-state #call-layout > div.flex-col { justify-content: center !important; }
        .phone-zoomed-state #call-layout img, .phone-shrinking-state #call-layout img { max-width: 250px !important; margin-bottom: 0 !important; object-fit: contain !important; }
        .phone-zoomed-state #call-layout .bg-gray-900\/90, .phone-shrinking-state #call-layout .bg-gray-900\/90 { justify-content: center !important; }
        .phone-zoomed-state #ui-call-msg-internal, .phone-shrinking-state #ui-call-msg-internal { font-size: 1.35rem !important; line-height: 1.8 !important; }

        .mockup-notch {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 24px;
            background-color: #1a1a1a;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            z-index: 50;
            transition: width 0.6s ease;
        }
        
        /* 🚨 DYNAMIC PLATFORM CLASSES 🚨 */
        .header-whatsapp { background-color: #075E54; color: white; border-bottom: none; }
        .body-whatsapp { background-color: #E5DDD5; }
        .bubble-whatsapp { background-color: white; color: #000000 !important; border-top-left-radius: 0; margin-right: auto; }
        .bubble-whatsapp p, .bubble-whatsapp span { color: #000000 !important; }

        .header-telegram { background-color: #2481cc; color: white; border-bottom: none; }
        .body-telegram { background-color: #9fbdd6; }
        .bubble-telegram { background-color: white; color: #000000 !important; border-bottom-left-radius: 0; margin-right: auto; }
        .bubble-telegram p, .bubble-telegram span { color: #000000 !important; }

        .header-sms { background-color: #1f2937; color: white; border-bottom: none; }
        .body-sms { background-color: #ffffff; }
        .bubble-sms { background-color: #3b82f6 !important; border-bottom-right-radius: 0; margin-left: auto; }
        .bubble-sms p, .bubble-sms span { color: #ffffff !important; }

        .header-call { background-color: #111827; color: white; border-bottom: 1px solid #374151; }
        .body-call { background-color: #000000; }

        .phone-scroll::-webkit-scrollbar { width: 4px; }
        .phone-scroll::-webkit-scrollbar-track { background: transparent; }
        .phone-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.2); border-radius: 10px; }

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

    <audio id="room-music" src="{{ asset('audio/Terran3.mp3') }}" loop preload="auto" crossorigin="anonymous" class="hidden"></audio>
    <audio id="epic-audio" preload="auto" class="hidden"><source src="{{ asset('audio/countdown-epic.mp3') }}" type="audio/mpeg"></audio>
    <audio id="correct-audio" preload="auto" class="hidden"><source src="{{ asset('audio/right-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="wrong-audio" preload="auto" class="hidden"><source src="{{ asset('audio/wrong-answer.mp3') }}" type="audio/mpeg"></audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto" class="hidden"></audio>

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-30 theme-video">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 theme-vid-overlay mix-blend-color"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-transparent to-black/95 light-mode:hidden"></div>
        <div class="absolute inset-0 scanlines opacity-40"></div>
    </div>

    <div class="fixed top-4 right-4 z-[9999999] flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-amber-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-amber-500 hover:text-black transition-colors text-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.2)] pointer-events-auto">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-amber-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-amber-500 hover:text-black transition-colors text-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.2)] pointer-events-auto">
            ☀️ LIGHT MODE
        </button>

    <a href="{{ route('agent.mission') }}" class="px-5 py-2 theme-card border border-purple-500/50 theme-title rounded hover:bg-purple-600 hover:text-white transition-all text-[10px] md:text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(147,51,234,0.3)] flex items-center justify-center gap-2">
                <span data-en="ABORT" data-ms="BATAL">ABORT</span>
            </a>

    </div>

    <div id="custom-zoom-backdrop" class="custom-zoom-backdrop"></div>
    <button id="custom-close-btn" class="custom-close-btn" onclick="togglePhoneZoom()" title="Close Expanded View">
        <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <div id="flash-bang-container" class="fixed inset-0 z-[99995] pointer-events-none"></div>

    <div id="countdown-screen" class="hidden fixed inset-0 z-[99990] flex items-center justify-center epic-bg overflow-hidden pointer-events-none">
        <div class="particles"></div>
        <div id="lens-flare" class="absolute w-[200%] h-2 bg-white shadow-[0_0_60px_20px_#fcd34d] opacity-0 -rotate-12 z-20 mix-blend-screen pointer-events-none"></div>
        <div id="countdown-number" class="relative z-10 text-[18rem] md:text-[25rem] epic-text pointer-events-none opacity-0">3</div>
    </div>

    <div id="tutorial-modal" class="hidden fixed inset-0 z-[99900] flex-col items-center justify-center p-4 transition-opacity duration-500 opacity-0 bg-black/80 backdrop-blur-md">
        <div class="theme-card border border-amber-500/50 max-w-4xl w-full max-h-[95vh] overflow-y-auto p-6 md:p-8 rounded-xl shadow-[0_0_40px_rgba(245,158,11,0.3)] flex flex-col pointer-events-auto">
            
            <h2 class="theme-title text-2xl font-bold uppercase tracking-widest mb-4 flex items-center gap-3 shrink-0">
                <svg class="w-6 h-6 animate-pulse text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="translation-target" data-en="Pre-Mission Guidance" data-ms="Panduan Pra-Misi">Panduan Pra-Misi</span>
            </h2>
            
            <div class="relative w-full bg-black border border-gray-700 rounded-lg mb-6 overflow-hidden flex justify-center shadow-inner shrink-0" style="padding-bottom: 56.25%;">
                <video id="tutorial-video" controls class="absolute top-0 left-0 w-full h-full object-contain">
                    <source src="{{ asset('video/tutorial3.mp4') }}" type="video/mp4">
                </video>
            </div>
            
            <div class="bg-amber-500/10 border-l-4 border-amber-500 p-4 mb-6 rounded text-left shrink-0">
                <p class="theme-value font-mono text-sm leading-relaxed"    
                 data-ms="Sila tonton video panduan di atas. Beri perhatian kepada taktik kejuruteraan sosial yang digunakan. Anda perlu menilai setiap mesej untuk mengesan tanda-tanda penipuan (Vishing, Smishing). Tutup tetingkap ini apabila anda bersedia."
                 data-en="Please watch the video guide above. Pay attention to the social engineering tactics used. You need to evaluate each message to detect signs of fraud (Vishing, Smishing). Close this window when you're ready.">
                 Sila tonton video panduan di atas. Beri perhatian kepada taktik kejuruteraan sosial yang digunakan. Anda perlu menilai setiap mesej untuk mengesan tanda-tanda penipuan (Vishing, Smishing). Tutup tetingkap ini apabila anda bersedia.
                </p>
            </div>
            
            <button onclick="closeTutorialAndStart()" class="relative z-50 w-full bg-amber-600 hover:bg-amber-500 text-black font-bold py-3 rounded uppercase tracking-widest shadow-[0_0_15px_rgba(245,158,11,0.4)] transition-all pointer-events-auto shrink-0">
                <span class="translation-target" data-en="Close & Begin Analysis" data-ms="Tutup & Mulakan Analisis">Tutup & Mulakan Analisis</span>
            </button>
        </div>
    </div>

    <div id="feedback-overlay" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity duration-300">
        <div id="feedback-box" class="theme-card border-2 border-amber-500/50 p-8 md:p-10 rounded-2xl max-w-2xl w-[90%] shadow-[0_0_40px_rgba(0,0,0,0.8)] zoom-in relative flex flex-col items-center text-center pointer-events-auto">
            <div class="flex flex-col items-center justify-center mb-6 border-b border-gray-700/50 pb-5 w-full">
                <p class="text-sm md:text-base uppercase tracking-widest text-amber-500 font-bold mb-3" data-en="ANALYSIS REPORT" data-ms="LAPORAN ANALISIS">ANALYSIS REPORT</p>
                <span id="feedback-status"></span>
            </div>
            <p class="text-lg md:text-2xl font-bold text-gray-300 dynamic-translation mb-8 leading-relaxed" id="ui-explanation">--</p>
            <button onclick="nextScenario()" class="btn-blink w-full md:w-3/4 bg-amber-600 hover:bg-amber-500 text-black font-extrabold uppercase tracking-widest text-lg md:text-xl px-8 py-4 rounded-lg transition-colors shadow-[0_0_20px_rgba(245,158,11,0.5)] pointer-events-auto">
                <span class="translation-target" data-en="Acknowledge" data-ms="Sahkan">Acknowledge</span>
            </button>
        </div>
    </div>

    <div class="game-container relative z-50 p-4 mt-8 md:mt-0" id="main-game-wrapper">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 px-2 gap-4 sc-anim sc-in-left">
            <div>
                <h1 class="title-font text-3xl text-amber-500 tracking-widest uppercase mb-1 drop-shadow-[0_0_10px_rgba(245,158,11,0.8)]">S.H.I.E.L.D. COMM INTERCEPTOR</h1>
                <p class="text-amber-600 font-bold uppercase tracking-widest text-xs" data-en="Mission 03: The Human Firewall" data-ms="Misi 03: Tembok Api Manusia">Mission 03: The Human Firewall</p>
            </div>
            
            <div class="flex items-center gap-3 bg-black/40 border border-amber-900/50 px-4 py-2 rounded-lg backdrop-blur-sm self-start md:self-auto">
                <div class="text-gray-400 text-[10px] uppercase tracking-widest font-bold whitespace-nowrap" data-en="Defense Integrity (Mistakes Allowed: 3)" data-ms="Integriti Pertahanan (Kesilapan Dibenarkan: 3)">
                    Defense Integrity <span class="hidden sm:inline">(Mistakes Allowed: 3)</span>
                </div>
                <div class="flex gap-1" id="lives-container">
                    <svg id="life-1" class="w-5 h-5 text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-all" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg id="life-2" class="w-5 h-5 text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-all" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <svg id="life-3" class="w-5 h-5 text-red-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.8)] transition-all" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </div>
            </div>
        </div>

        <div id="briefing-screen" class="terminal-box flex flex-col h-[650px] justify-center items-center p-8 text-center bg-black sc-anim sc-in-right tv-turn-on">
            <h2 class="title-font text-4xl mb-4 uppercase tracking-wider text-amber-500 drop-shadow-[0_0_15px_rgba(245,158,11,0.8)]" data-en="MISSION BRIEFING" data-ms="TAKLIMAT MISI">MISSION BRIEFING</h2>
            
            <div class="max-w-2xl text-gray-300 space-y-6 mb-10 text-base md:text-lg font-bold leading-relaxed">
                <p data-en="Welcome to <span class='text-amber-400 font-bold'>The Human Firewall</span>. Attackers often target individuals directly through text messages, social media, and messaging platforms." data-ms="Selamat datang ke <span class='text-amber-400 font-bold'>Tembok Api Manusia</span>. Penyerang sering menyasarkan individu secara terus melalui mesej teks, media sosial, dan platform pemesejan.">
                    Welcome to <span class="text-amber-400 font-bold">The Human Firewall</span>. Attackers often target individuals directly through text messages, social media, and messaging platforms.
                </p>
                <p data-en="Your objective is to intercept active communications. Carefully read the incoming messages and determine if they are safe or if they contain a social engineering threat." data-ms="Objektif anda adalah untuk memintas komunikasi aktif. Baca dengan teliti mesej yang masuk dan tentukan sama ada ia selamat atau jika ia mengandungi ancaman kejuruteraan sosial.">
                    Your objective is to intercept active communications. Carefully read the incoming messages and determine if they are safe or if they contain a social engineering threat.
                </p>
                <p class="text-red-500 font-extrabold" data-en="WARNING: You are only permitted 3 mistakes before the system locks you out." data-ms="AMARAN: Anda hanya dibenarkan 3 kesilapan sebelum sistem mengunci anda.">
                    WARNING: You are only permitted 3 mistakes before the system locks you out.
                </p>
                <p class="italic text-amber-600 uppercase tracking-widest mt-4 font-extrabold" data-en="Good luck, Agent." data-ms="Semoga berjaya, Ejen.">Good luck, Agent.</p>
            </div>

            <button id="launch-btn" class="relative z-50 bg-amber-600 text-black px-10 py-4 rounded font-bold text-xl uppercase tracking-widest hover:bg-amber-500 transition-colors shadow-[0_0_20px_rgba(245,158,11,0.6)] cursor-pointer pointer-events-auto">
                <span data-en="Launch Mission" data-ms="Mulakan Misi">Launch Mission</span>
            </button>
        </div>

        <div id="game-terminal" class="terminal-box flex flex-col h-[650px] hidden">
            <div class="terminal-header shrink-0">
                <div class="text-amber-600 font-extrabold tracking-widest uppercase text-sm md:text-base flex items-center gap-3">
                    <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.8)]"></span>
                    <span data-en="Live Intercept" data-ms="Pintasan Langsung">Live Intercept</span> <span id="progress-counter">(1/?)</span>
                </div>
                <span class="text-amber-700 text-xs md:text-sm font-bold uppercase tracking-widest hidden md:inline-block" data-en="ENCRYPTED CONNECTION" data-ms="SAMBUNGAN DISULITKAN">ENCRYPTED CONNECTION</span>
            </div>

            <div class="flex-1 p-4 md:p-8 overflow-y-auto flex flex-col md:flex-row items-center justify-center gap-6 md:gap-12 bg-black/40" id="scenario-display">
                
                <div class="relative shrink-0 w-[320px] h-[480px] flex items-center justify-center" id="phone-wrapper">
                    
                    <div id="phone-screen" class="mockup-phone">
                        <div class="mockup-notch"></div>

                        <div id="phone-header" class="pt-8 pb-3 px-4 flex items-center justify-between shrink-0 shadow-sm transition-colors duration-500">
                            <div class="flex items-center gap-3 flex-1 overflow-hidden">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-bold text-lg border border-white/30 backdrop-blur-sm shrink-0 always-white" id="ui-avatar">?</div>
                                <div class="flex-1 overflow-hidden pr-2">
                                    <div id="ui-sender" class="font-bold truncate text-base leading-tight font-sans always-white">Sender Name</div>
                                    <div class="text-[10px] opacity-80 font-sans mt-0.5 flex items-center gap-1 always-white">
                                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                        <span data-en="online" data-ms="dalam talian">online</span>
                                    </div>
                                </div>
                            </div>
                            <div id="ui-platform-badge" class="px-2.5 py-1 rounded bg-black/30 border border-white/20 backdrop-blur-md text-[9px] md:text-[10px] font-black tracking-widest uppercase shadow-md shrink-0 always-white">PLATFORM</div>
                        </div>

                        <div id="phone-content" class="flex-1 p-4 phone-scroll overflow-y-auto flex flex-col transition-colors duration-500 relative">
                            
                            <div id="chat-layout" class="w-full flex mt-auto">
                                <div id="chat-bubble" class="p-3 md:p-4 rounded-2xl shadow-md transition-colors text-left relative w-[85%] font-sans text-sm">
                                    <p id="ui-chat-message" class="whitespace-pre-wrap leading-relaxed dynamic-translation always-black" data-original="Message content...">Message content...</p>
                                    <span class="text-[9px] opacity-60 absolute bottom-1 right-3 font-sans always-black" id="ui-time">10:42 AM</span>
                                </div>
                            </div>

                            <div id="call-layout" class="hidden flex-col items-center h-full w-full pt-2 pb-2 gap-3">
                                <div class="flex flex-col items-center justify-center shrink-0">
                                    <p class="text-emerald-400 text-[10px] animate-pulse mb-2 font-mono tracking-widest text-center" data-en="LIVE INTERCEPT..." data-ms="PINTASAN LANGSUNG...">LIVE INTERCEPT...</p>
                                    <img src="{{ asset('img/incoming-call.png') }}" class="w-full max-w-[150px] object-contain drop-shadow-xl rounded-xl border border-gray-700/50" alt="Incoming Call">
                                </div>
                                
                                <div class="w-full flex-1 bg-gray-900/90 border border-gray-700 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                                    <div class="flex items-center gap-2 mb-2 border-b border-gray-700 pb-2 shrink-0">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest" data-en="AUDIO TRANSCRIPT" data-ms="TRANSKRIP AUDIO">AUDIO TRANSCRIPT</span>
                                    </div>
                                    <p id="ui-call-msg-internal" class="text-sm font-mono italic dynamic-translation always-white" data-original="Transcript...">Transcript...</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button onclick="togglePhoneZoom()" class="custom-enlarge-btn" title="Enlarge Screen">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                    </button>

                </div>

                <div id="side-transcript-panel" class="hidden flex-col w-full max-w-md theme-card border border-amber-500/50 rounded-2xl p-6 shadow-2xl transition-colors duration-500">
                    <div class="flex items-center gap-3 border-b border-gray-500/30 pb-3 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                        <span class="font-bold uppercase tracking-widest text-amber-500 text-sm" data-en="Live Audio Transcript" data-ms="Transkrip Audio Langsung">Live Audio Transcript</span>
                    </div>
                    <p id="ui-call-transcript-side" class="text-lg md:text-xl font-mono italic dynamic-translation leading-relaxed theme-value" data-original="Transcript...">Transcript...</p>
                </div>

            </div>

            <div class="p-6 bg-black/10 border-t border-amber-900/30 flex gap-4 shrink-0" id="action-buttons">
                <button onclick="submitAnswer(false)" class="flex-1 bg-emerald-900/30 hover:bg-emerald-600 text-emerald-500 hover:text-white border border-emerald-600 font-bold py-4 rounded transition-all uppercase tracking-widest text-sm md:text-base pointer-events-auto">
                    <span data-en="Verify as Safe" data-ms="Sahkan Selamat">Verify as Safe</span>
                </button>
                <button onclick="submitAnswer(true)" class="flex-1 bg-red-900/30 hover:bg-red-600 text-red-500 hover:text-white border border-red-600 font-bold py-4 rounded transition-all uppercase tracking-widest text-sm md:text-base shadow-[0_0_10px_rgba(220,38,38,0.2)] pointer-events-auto">
                    <span data-en="Mark as Threat" data-ms="Tanda Ancaman">Mark as Threat</span>
                </button>
            </div>
        </div>

        <div id="end-screen" class="terminal-box h-[650px] hidden flex-col items-center justify-center text-center p-8">
            <h2 id="end-title" class="title-font text-5xl mb-4 uppercase tracking-wider text-amber-500 drop-shadow-[0_0_15px_rgba(245,158,11,0.8)]" data-en="FIREWALL SECURED" data-ms="TEMBOK API SELAMAT">FIREWALL SECURED</h2>
            <p id="end-msg" class="text-gray-400 mb-8 max-w-md mx-auto leading-relaxed" data-en="All social engineering attempts successfully blocked. You have earned the third fragment of the master password." data-ms="Semua cubaan kejuruteraan sosial berjaya disekat. Anda telah memperoleh serpihan ketiga kata laluan utama.">All social engineering attempts successfully blocked. You have earned the third fragment of the master password.</p>
            
            <div id="frag-box" class="w-24 h-24 border-2 border-amber-500 flex items-center justify-center text-5xl font-bold text-white shadow-[0_0_20px_rgba(245,158,11,0.5)] mb-8 bg-amber-900/30">
                C
            </div>

            <div class="flex flex-col gap-4">
                <a id="end-btn" href="#" class="bg-amber-600 text-black px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-amber-500 transition-colors shadow-[0_0_15px_rgba(245,158,11,0.4)] pointer-events-auto">
                    <span data-en="Return to Hub" data-ms="Kembali ke Hab">Return to Hub</span>
                </a>
                
                <a id="fail-return-btn" href="{{ route('agent.mission') }}" class="hidden bg-gray-800 px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-gray-700 transition-colors border border-gray-600 always-white pointer-events-auto">
                    <span data-en="Return to Mission Control" data-ms="Kembali ke Papan Misi" class="always-white">Return to Mission Control</span>
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

        // --- Global Dictionary Cache for Translations ---
        window.translationCache = {};

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

        if (currentTheme === 'light') { bodyEl.classList.add('light-mode'); if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    el.innerHTML = el.getAttribute(`data-${lang}`);
                }
            });
            
            if(document.getElementById('ui-chat-message') && document.getElementById('ui-chat-message').getAttribute('data-original') !== "Message content...") {
                 processDynamicTranslations(lang);
            }
        }

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

        // --- GAME LOGIC ---
        const scenarios = @json($scenarios);
        
        let currentIndex = 0;
        let mistakes = 0;
        const MAX_MISTAKES = 3;
        let wrongAnswersLog = [];

        // UI DOM Elements
        const uiPlatformBadge = document.getElementById('ui-platform-badge');
        const uiSender = document.getElementById('ui-sender');
        const uiAvatar = document.getElementById('ui-avatar');
        
        const uiChatMsg = document.getElementById('ui-chat-message');
        const uiCallMsgInternal = document.getElementById('ui-call-msg-internal');
        const uiTime = document.getElementById('ui-time');
        
        const phoneHeader = document.getElementById('phone-header');
        const phoneContent = document.getElementById('phone-content');
        const chatLayout = document.getElementById('chat-layout');
        const callLayout = document.getElementById('call-layout');
        const chatBubble = document.getElementById('chat-bubble');
        const sideTranscriptPanel = document.getElementById('side-transcript-panel');

        const uiExplanation = document.getElementById('ui-explanation');
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
        
        const actionBtns = document.getElementById('action-buttons');
        const feedbackOverlay = document.getElementById('feedback-overlay');
        const feedbackBox = document.getElementById('feedback-box');
        const statusBadge = document.getElementById('feedback-status');

        const epicAudio = document.getElementById('epic-audio');
        const correctAudio = document.getElementById('correct-audio');
        const wrongAudio = document.getElementById('wrong-audio');

        // 🚨 DOM LIFTING ZOOM FUNCTION LOGIC 🚨
        let isPhoneZoomed = false;
        function togglePhoneZoom() {
            const phone = document.getElementById('phone-screen');
            const wrapper = document.getElementById('phone-wrapper');
            const backdrop = document.getElementById('custom-zoom-backdrop');
            const closeBtn = document.getElementById('custom-close-btn');

            if (!isPhoneZoomed) {
                document.body.appendChild(phone);
                backdrop.classList.add('active');
                closeBtn.classList.add('active');
                phone.classList.remove('phone-shrinking-state');
                void phone.offsetWidth;
                phone.classList.add('phone-zoomed-state');
                isPhoneZoomed = true;
            } else {
                backdrop.classList.remove('active');
                closeBtn.classList.remove('active');
                phone.classList.remove('phone-zoomed-state');
                phone.classList.add('phone-shrinking-state');
                isPhoneZoomed = false;

                setTimeout(() => {
                    if (!isPhoneZoomed) {
                        phone.classList.remove('phone-shrinking-state');
                        wrapper.insertBefore(phone, wrapper.firstChild);
                    }
                }, 400); 
            }
        }

        // 🔥 SYNCED COUNTDOWN & FLASH ENGINE 🔥
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

            if (roomMusic.paused) {
                roomMusic.play().catch(e => console.log("Audio play blocked"));
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
                        countdownNumber.classList.remove('text-amber-500');
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

        // 🔥 CLOSE TUTORIAL & START ACTUAL GAME 🔥
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
                    uiChatMsg.innerText = currentLang === 'ms' ? "RALAT SISTEM: TIADA SENARIO DIKONFIGURASI." : "SYSTEM ERROR: NO SCENARIOS CONFIGURED.";
                    uiChatMsg.classList.add('text-red-500', 'font-mono');
                    actionBtns.style.display = 'none';
                }
            }, 500);
        }

        function loadScenario() {
            if (currentIndex >= scenarios.length) {
                endGame(true);
                return;
            }

            const s = scenarios[currentIndex];
            const platformStr = (s.platform || '').toLowerCase();
            const isCall = platformStr.includes('call') || platformStr.includes('telefon') || platformStr.includes('panggilan');
            const isWhatsApp = platformStr.includes('whatsapp') || platformStr.includes('wasap');
            const isTelegram = platformStr.includes('telegram');
            
            actionBtns.classList.remove('hidden');
            
            // DYNAMIC PLATFORM STYLING
            uiPlatformBadge.innerText = s.platform;
            uiSender.innerText = s.sender_name || 'Unknown';
            uiAvatar.innerText = (s.sender_name || 'U').charAt(0).toUpperCase();

            // Set current time dynamically
            const d = new Date();
            uiTime.innerText = d.getHours() + ':' + (d.getMinutes()<10?'0':'') + d.getMinutes();

            // Reset base classes
            phoneHeader.className = 'pt-8 pb-3 px-4 flex items-center justify-between shrink-0 shadow-sm transition-colors duration-500';
            phoneContent.className = 'flex-1 p-4 phone-scroll overflow-y-auto flex flex-col transition-colors duration-500 relative';
            chatBubble.className = 'p-3 md:p-4 rounded-2xl shadow-md transition-colors text-left relative mt-auto w-[85%] font-sans text-sm';

            if (isCall) {
                chatLayout.classList.add('hidden');
                
                callLayout.classList.remove('hidden');
                callLayout.classList.add('flex');
                
                phoneHeader.classList.add('header-call');
                phoneContent.classList.add('body-call', 'justify-center');
                
                uiCallMsgInternal.setAttribute('data-original', `"...${s.message}..."`);
            } 
            else {
                callLayout.classList.add('hidden');
                chatLayout.classList.remove('hidden');
                
                if (isWhatsApp) {
                    phoneHeader.classList.add('header-whatsapp');
                    phoneContent.classList.add('body-whatsapp');
                    chatBubble.classList.add('bubble-whatsapp');
                } 
                else if (isTelegram) {
                    phoneHeader.classList.add('header-telegram');
                    phoneContent.classList.add('body-telegram');
                    chatBubble.classList.add('bubble-telegram');
                } 
                else {
                    phoneHeader.classList.add('header-sms');
                    phoneContent.classList.add('body-sms');
                    chatBubble.classList.add('bubble-sms'); 
                }
                
                uiChatMsg.setAttribute('data-original', s.message);
            }

            uiExplanation.setAttribute('data-original', s.explanation);
            progressCounter.innerText = `(${currentIndex + 1}/${scenarios.length})`;
            
            const displayArea = document.getElementById('scenario-display');
            displayArea.classList.remove('fade-in');
            void displayArea.offsetWidth;
            displayArea.classList.add('fade-in');

            processDynamicTranslations(currentLang);
        }

        let isSubmitting = false;

        function submitAnswer(playerGuessedThreat) {
            if (isSubmitting) return;
            isSubmitting = true;

            setTimeout(() => { isSubmitting = false; }, 1500);

            const currentScenario = scenarios[currentIndex];
            const isActuallyThreat = currentScenario.is_threat == 1; 

            actionBtns.classList.add('hidden'); 
            
            feedbackOverlay.classList.remove('hidden');
            feedbackOverlay.classList.add('flex');
            
            feedbackBox.classList.remove('zoom-in');
            void feedbackBox.offsetWidth;
            feedbackBox.classList.add('zoom-in');

            if (playerGuessedThreat === isActuallyThreat) {
                if(correctAudio) {
                    correctAudio.currentTime = 0;
                    correctAudio.play();
                }
                
                feedbackBox.classList.replace('border-amber-500/50', 'border-emerald-500');
                feedbackBox.classList.replace('border-red-500', 'border-emerald-500');
                feedbackBox.style.boxShadow = "0 0 40px rgba(16, 185, 129, 0.4)";
                
                statusBadge.innerText = currentLang === 'ms' ? "PINTASAN BERJAYA" : "CORRECT INTERCEPT";
                statusBadge.className = "feedback-badge-success text-xs md:text-sm font-extrabold uppercase tracking-widest px-4 py-2 rounded bg-emerald-900/50 text-emerald-400 border border-emerald-500/50";
            } else {
                if(wrongAudio) {
                    wrongAudio.currentTime = 0;
                    wrongAudio.play();
                }
                
                feedbackBox.classList.replace('border-amber-500/50', 'border-red-500');
                feedbackBox.classList.replace('border-emerald-500', 'border-red-500');
                feedbackBox.style.boxShadow = "0 0 40px rgba(239, 68, 68, 0.4)";
                
                statusBadge.innerText = currentLang === 'ms' ? "PENILAIAN SALAH" : "INCORRECT ASSESSMENT";
                statusBadge.className = "feedback-badge-fail text-xs md:text-sm font-extrabold uppercase tracking-widest px-4 py-2 rounded bg-red-900/50 text-red-400 border border-red-500/50";
                
                let guessedStatus = playerGuessedThreat ? "Threat" : "Safe";
                let actualStatus = isActuallyThreat ? "Threat" : "Safe";
                let sender = currentScenario.sender_name || "Unknown";
                let msg = currentScenario.message || "";
                let shortMsg = msg.length > 30 ? msg.substring(0, 30) + "..." : msg;
                wrongAnswersLog.push(`Scenario ${currentIndex + 1} from ${sender} ("${shortMsg}"): Marked as ${guessedStatus}, but was actually ${actualStatus}`);
                
                takeDamage();
            }
        }
        
        function nextScenario() {
            feedbackOverlay.classList.add('hidden');
            feedbackOverlay.classList.remove('flex');
            
            feedbackBox.className = "theme-card border-2 border-amber-500/50 p-8 md:p-10 rounded-2xl max-w-2xl w-[90%] shadow-[0_0_40px_rgba(0,0,0,0.8)] zoom-in relative flex flex-col items-center text-center pointer-events-auto";
            feedbackBox.style.boxShadow = "";
            
            if(isPhoneZoomed) togglePhoneZoom();
            
            currentIndex++;
            loadScenario();
        }

        function takeDamage() {
            mistakes++;
            
            terminalBox.classList.remove('shake');
            void terminalBox.offsetWidth;
            terminalBox.classList.add('shake');
            
            if(mistakes <= MAX_MISTAKES) {
                const heart = document.getElementById(`life-${4 - mistakes}`);
                if (heart) {
                    heart.classList.remove('text-red-500', 'drop-shadow-[0_0_10px_rgba(239,68,68,0.8)]');
                    heart.classList.add('text-gray-700', 'opacity-50');
                }
            }

            if (mistakes >= MAX_MISTAKES) {
                setTimeout(() => { endGame(false); }, 1000); 
            }
        }

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
                endTitle.classList.replace('text-amber-500', 'text-red-500');
                endTitle.classList.replace('drop-shadow-[0_0_15px_rgba(245,158,11,0.8)]', 'drop-shadow-[0_0_15px_rgba(239,68,68,0.8)]');
                
                const endMsg = document.getElementById('end-msg');
                endMsg.setAttribute('data-en', "Please strengthen your understanding of this room's module and try again. You can do it!");
                endMsg.setAttribute('data-ms', "Sila mantapkan lagi kefahaman anda terhadap modul bilik ini dan cuba lagi. Anda pasti boleh!");
                endMsg.innerText = currentLang === 'ms' 
                    ? "Sila mantapkan lagi kefahaman anda terhadap modul bilik ini dan cuba lagi. Anda pasti boleh!" 
                    : "Please strengthen your understanding of this room's module and try again. You can do it!";
                    
                document.getElementById('frag-box').classList.add('hidden'); 
                
                const endBtn = document.getElementById('end-btn');
                endBtn.innerHTML = currentLang === 'ms' ? '<span data-en="Retry Module" data-ms="Cuba Semula Modul">Cuba Semula Modul</span>' : '<span data-en="Retry Module" data-ms="Cuba Semula Modul">Retry Module</span>';
                endBtn.removeAttribute('onclick');
                endBtn.setAttribute('href', "javascript:location.reload()");
                endBtn.classList.replace('bg-amber-600', 'bg-red-600');
                endBtn.classList.replace('hover:bg-amber-500', 'hover:bg-red-500');
                endBtn.classList.replace('shadow-[0_0_15px_rgba(245,158,11,0.4)]', 'shadow-[0_0_15px_rgba(239,68,68,0.4)]');
                
                const failReturnBtn = document.getElementById('fail-return-btn');
                if (failReturnBtn) {
                    failReturnBtn.classList.remove('hidden');
                    failReturnBtn.classList.add('inline-block');
                }
                
                endScreen.style.borderColor = "#ef4444";
                
                logRoomFailure(3);
                
            } else {
                let totalQuestions = scenarios.length; 
                let correctAnswers = totalQuestions - mistakes; 
                let finalScore = correctAnswers * 100; 
                
                const encodedWrongs = encodeURIComponent(JSON.stringify(wrongAnswersLog));
                
                const proceedBtn = document.getElementById('end-btn');
                proceedBtn.setAttribute('href', `{{ route('agent.level3.complete') }}?score=${finalScore}&correct=${correctAnswers}&incorrect=${mistakes}&wrong_answers=${encodedWrongs}`);
            }
            
            applyLanguage(currentLang);
        }
    </script>
    
@include('partials.cursor')
</body>
</html>