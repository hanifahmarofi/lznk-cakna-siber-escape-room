<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // {{ $game->title }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        /* --- HIGH CONTRAST THEME SYSTEM --- */
        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --card-bg: rgba(21, 21, 21, 0.85);
            --border-color: rgba(168, 85, 247, 0.5);
            --title-color: #a855f7; /* Purple 500 */
            --vid-filter: none;
            --vid-overlay: rgba(88, 28, 135, 0.2);
        }

        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: rgba(147, 51, 234, 0.6);
            --title-color: #7e22ce; /* Purple 700 */
            --vid-filter: invert(1) hue-rotate(180deg) brightness(1.5);
            --vid-overlay: rgba(255, 255, 255, 0.5);
        }

        body { 
            font-family: 'Share Tech Mono', monospace; 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .theme-card { background-color: var(--card-bg) !important; border-color: var(--border-color) !important; transition: all 0.3s ease; }
        .theme-title { color: var(--title-color) !important; transition: color 0.3s ease; }
        .theme-video { filter: var(--vid-filter); transition: filter 0.5s ease; }
        .theme-vid-overlay { background-color: var(--vid-overlay); transition: background-color 0.5s ease; }

        /* Light Mode Overrides for Specific Elements */
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-gray-400 { color: #4b5563 !important; }
        .light-mode .text-gray-500 { color: #374151 !important; }
        .light-mode .text-purple-400 { color: #7e22ce !important; }
        .light-mode .text-purple-200 { color: #4c1d95 !important; }
        .light-mode .bg-black\/60 { background-color: rgba(255,255,255,0.85) !important; }
        .light-mode .bg-black\/40 { background-color: rgba(243,244,246,0.8) !important; }
        .light-mode .border-gray-800 { border-color: #d1d5db !important; }
        .light-mode .terminal-screen { background-color: #f3f4f6 !important; border-color: #9333ea !important; box-shadow: inset 0 0 10px rgba(147, 51, 234, 0.1) !important; }

        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }
        
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }

        /* ACTION BUTTONS */
        .action-btn {
            border-radius: 0.5rem;
            padding: 1rem 2rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .action-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
        
        .btn-true { background-color: rgba(16, 185, 129, 0.2); border: 2px solid #10b981; color: #10b981; }
        .btn-true:hover:not(:disabled) { background-color: rgba(16, 185, 129, 0.8); color: #000; box-shadow: 0 0 20px rgba(16, 185, 129, 0.6); transform: translateY(-2px); }
        
        .btn-false { background-color: rgba(239, 68, 68, 0.2); border: 2px solid #ef4444; color: #ef4444; }
        .btn-false:hover:not(:disabled) { background-color: rgba(239, 68, 68, 0.8); color: #000; box-shadow: 0 0 20px rgba(239, 68, 68, 0.6); transform: translateY(-2px); }

        /* TERMINAL SCREEN */
        .terminal-screen {
            background-color: #050505;
            border: 2px solid rgba(168, 85, 247, 0.4);
            border-radius: 0.5rem;
            padding: 1.5rem;
            min-height: 8rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: inset 0 0 20px rgba(168, 85, 247, 0.1);
            transition: all 0.3s ease;
        }

        /* LIVES STYLING */
        .strike-orb {
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: #ef4444;
            box-shadow: 0 0 10px #ef4444;
            transition: all 0.3s;
        }
        .strike-orb.lost {
            background-color: #374151;
            box-shadow: none;
            opacity: 0.5;
        }

        /* --- STABLE CSS JUMP ANIMATIONS --- */
        @keyframes popIn { 0% { transform: scale(0.5); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        
        @keyframes leapForward {
            0% { transform: translate(-60px, -40px) scale(1.5) rotate(-15deg); opacity: 0; filter: drop-shadow(0 20px 5px rgba(0,0,0,0.5)); }
            50% { transform: translate(-30px, -60px) scale(1.8) rotate(10deg); opacity: 1; filter: drop-shadow(0 30px 10px rgba(0,0,0,0.3)); }
            100% { transform: translate(0, 0) scale(1) rotate(0deg); opacity: 1; filter: drop-shadow(0 0 10px rgba(168,85,247,0.8)); }
        }
        
        @keyframes leapBackward {
            0% { transform: translate(60px, -40px) scale(1.5) rotate(15deg); opacity: 0; filter: drop-shadow(0 20px 5px rgba(0,0,0,0.5)); }
            50% { transform: translate(30px, -60px) scale(1.8) rotate(-10deg); opacity: 1; filter: drop-shadow(0 30px 10px rgba(0,0,0,0.3)); }
            100% { transform: translate(0, 0) scale(1) rotate(0deg); opacity: 1; filter: drop-shadow(0 0 10px rgba(239,68,68,0.8)); }
        }

        .tile-animate { animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; display: inline-block; }
        .leap-forward { animation: leapForward 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards; display: inline-block; z-index: 50; position: relative; }
        .leap-backward { animation: leapBackward 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards; display: inline-block; z-index: 50; position: relative; }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden flex flex-col items-center justify-center p-4">

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-30 theme-video">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 theme-vid-overlay mix-blend-color"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-transparent to-black/95 light-mode:hidden"></div>
        <div class="absolute inset-0 scanlines opacity-40"></div>
    </div>

    <div class="fixed top-0 left-0 w-full px-4 md:px-8 py-4 flex justify-between items-start md:items-center z-50 pointer-events-none">
        <div class="pointer-events-auto">
            <h1 class="title-font text-xl md:text-3xl theme-title uppercase drop-shadow-[0_0_10px_var(--title-glow)] dynamic-translation" data-original="{{ $game->title }}">{{ $game->title }}</h1>
            <p class="text-[10px] md:text-sm text-purple-500/70 font-bold tracking-widest uppercase" data-en="Logic Board Initiated" data-ms="Papan Logik Dimulakan">Logic Board Initiated</p>
        </div>
        
        <div class="pointer-events-auto flex flex-col md:flex-row items-end md:items-center gap-2 md:gap-4">
            <div class="flex gap-2">
                <button id="lang-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-[10px] md:text-xs font-bold tracking-wider hover:bg-purple-600 hover:text-white transition-colors shadow-[0_0_10px_rgba(168,85,247,0.3)] theme-title">
                    🇲🇾 BAHASA
                </button>
                <button id="theme-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-[10px] md:text-xs font-bold tracking-wider hover:bg-purple-600 hover:text-white transition-colors shadow-[0_0_10px_rgba(168,85,247,0.3)] theme-title">
                    ☀️ LIGHT MODE
                </button>
            </div>

            <a href="{{ route('agent.arcade') }}" class="px-5 py-2 theme-card border border-purple-500/50 theme-title rounded hover:bg-purple-600 hover:text-white transition-all text-[10px] md:text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(147,51,234,0.3)] flex items-center justify-center gap-2">
                <span data-en="ABORT" data-ms="BATAL">ABORT</span>
            </a>
        </div>
    </div>

    <div class="relative z-10 w-full max-w-4xl theme-card backdrop-blur-md rounded-xl p-6 md:p-8 text-center mt-24 mb-8 shadow-[0_0_30px_rgba(147,51,234,0.2)]" id="game-container">
        
        <div class="flex justify-between items-center mb-6 md:mb-8 border-b border-gray-800 pb-4 md:pb-6 transition-colors">
            <div class="text-left w-1/3">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" data-en="Statements" data-ms="Penyataan">Statements</p>
                <p class="text-lg md:text-2xl text-white font-bold font-mono"><span id="current-round">1</span> / <span id="total-rounds">X</span></p>
            </div>
            
            <div class="flex flex-col items-center w-1/3">
                <p class="text-[10px] theme-title uppercase tracking-widest font-bold mb-2" data-en="System Integrity" data-ms="Integriti Sistem">System Integrity</p>
                <div class="flex gap-2" id="lives-container">
                    <div class="strike-orb" id="strike-1"></div>
                    <div class="strike-orb" id="strike-2"></div>
                    <div class="strike-orb" id="strike-3"></div>
                </div>
            </div>

            <div class="text-right w-1/3">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" data-en="Max Reward" data-ms="Ganjaran Max">Max Reward</p>
                <p class="text-lg md:text-2xl text-emerald-400 font-bold font-mono">{{ $game->base_score }} PTS</p>
            </div>
        </div>

        @if($game->instruction)
            <p class="text-sm md:text-base text-gray-400 italic mb-6 px-4 dynamic-translation" data-original="{{ $game->instruction }}">{{ $game->instruction }}</p>
        @else
            <p class="text-sm md:text-base text-gray-400 italic mb-6 px-4" data-en="Move the Knight to the Star. Correct answers move you forward. Mistakes knock you back." data-ms="Gerakkan Knight ke Bintang. Jawapan betul maju ke depan. Kesilapan berundur ke belakang.">Move the Knight to the Star. Correct answers move you forward. Mistakes knock you back.</p>
        @endif

        <div class="mb-8 p-4 border border-gray-800 rounded-lg bg-black/40 relative transition-colors">
            <p class="text-[10px] text-purple-400 uppercase tracking-widest font-bold mb-3" data-en="Logic Path" data-ms="Laluan Logik">Logic Path</p>
            <div id="chess-board" class="flex flex-wrap justify-center gap-2 md:gap-3"></div>
        </div>

        <div class="terminal-screen mb-8 relative">
            <div class="absolute top-2 left-3 flex gap-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500/50"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/50"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/50"></div>
            </div>
            
            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-2 mt-4 text-left" data-en="Incoming Scenario:" data-ms="Senario Masuk:">Incoming Scenario:</p>
            <p id="statement-display" class="text-lg md:text-xl text-purple-200 font-bold px-4">LOADING STATEMENT...</p>
            
            <p id="feedback-display" class="text-sm font-bold uppercase tracking-widest mt-4 opacity-0 transition-opacity h-5"></p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <button id="btn-true" onclick="submitAnswer(1)" class="action-btn btn-true flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span data-en="TRUE" data-ms="BENAR">TRUE</span>
            </button>
            <button id="btn-false" onclick="submitAnswer(0)" class="action-btn btn-false flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                <span data-en="FALSE" data-ms="PALSU">FALSE</span>
            </button>
        </div>

    </div>

    <div id="success-overlay" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center p-4 pointer-events-auto">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.5)]">
            <span class="text-5xl" style="filter: drop-shadow(0 0 10px #10b981);">★</span>
        </div>
        <h2 class="title-font text-3xl md:text-4xl text-emerald-400 mb-2 tracking-widest uppercase text-center" data-en="Board Conquered" data-ms="Papan Ditawan">Board Conquered</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="You successfully reached the target node." data-ms="Anda berjaya mencapai nod sasaran.">You successfully reached the target node.</p>
        
        <form action="{{ route('agent.arcade.complete', $game->id) }}" method="POST">
            @csrf
            <input type="hidden" name="score" value="{{ $game->base_score }}">
            <input type="hidden" name="correct" id="track-correct" value="0">
            <input type="hidden" name="incorrect" id="track-incorrect" value="0">
            <input type="hidden" name="wrong_answers" id="track-wrong" value="[]">

            <button type="submit" class="px-8 py-3 md:px-10 md:py-4 bg-emerald-600 hover:bg-emerald-500 text-black rounded font-extrabold tracking-widest uppercase text-base md:text-lg shadow-[0_0_20px_rgba(16,185,129,0.6)] transition-all">
                <span data-en="CLAIM REWARD & RETURN" data-ms="TUNTUT GANJARAN">CLAIM REWARD & RETURN</span>
            </button>
        </form>
    </div>

    <div id="fail-overlay" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center p-4 pointer-events-auto">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-red-900/50 flex items-center justify-center text-red-500 border border-red-500 mb-6 shadow-[0_0_30px_rgba(239,68,68,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </div>
        <h2 class="title-font text-3xl md:text-4xl text-red-500 mb-2 tracking-widest uppercase text-center" data-en="Mission Failed" data-ms="Misi Gagal">Mission Failed</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="System integrity compromised." data-ms="Integriti sistem terkompromi.">System integrity compromised.</p>
        
        <button onclick="restartGame()" class="px-8 py-3 md:px-10 md:py-4 bg-red-600 hover:bg-red-500 text-white rounded font-extrabold tracking-widest uppercase text-base md:text-lg shadow-[0_0_20px_rgba(239,68,68,0.6)] transition-all">
            <span data-en="REBOOT SYSTEM" data-ms="MULA SEMULA SISTEM">REBOOT SYSTEM</span>
        </button>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>
    <audio id="sfx-right" src="{{ asset('audio/right-answer.mp3') }}" preload="auto"></audio>
    <audio id="sfx-wrong" src="{{ asset('audio/wrong-answer.mp3') }}" preload="auto"></audio>

    <script>
        // 🚨 1. LOAD GAME DATA FIRST
        const gameData = @json($game->game_data);
        
        let questions = [];
        if (gameData && gameData.questions) {
            let rawQ = Array.isArray(gameData.questions) ? gameData.questions : Object.values(gameData.questions);
            questions = rawQ.filter(q => q && q.statement && q.statement.trim() !== '');
        }

        let currentIndex = 0;
        let currentPos = 0;
        let isProcessing = false;

        // 🚨 UPGRADED LOGIC: Fix board size & add strikes
        let maxPos = Math.min(5, questions.length); 
        if (maxPos < 1) maxPos = 1;

        const maxMistakes = 3;
        let currentMistakes = 0;

        // 🚨 TRACKING VARIABLES
        let trackCorrect = 0;
        let trackIncorrect = 0;
        let trackWrongList = [];

        const statementEl = document.getElementById('statement-display');
        const feedbackEl = document.getElementById('feedback-display');
        const boardEl = document.getElementById('chess-board');
        const roundEl = document.getElementById('current-round');
        const totalEl = document.getElementById('total-rounds');
        const btnTrue = document.getElementById('btn-true');
        const btnFalse = document.getElementById('btn-false');
        const sfxRight = document.getElementById('sfx-right');
        const sfxWrong = document.getElementById('sfx-wrong');

        const clickSound = document.getElementById('ui-click-sound');

        function playClick() { if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); } }

        // --- SOUND INTERCEPTOR ---
        document.addEventListener("DOMContentLoaded", function() {
            if(clickSound) {
                clickSound.volume = 0.6; 
                
                document.querySelectorAll('button, a').forEach(element => {
                    element.addEventListener('click', function(e) {
                        clickSound.currentTime = 0; 
                        clickSound.play().catch(err => console.log(err));
                        
                        if (this.tagName.toLowerCase() === 'a' && this.hasAttribute('href')) {
                            let target = this.getAttribute('href');
                            if (target.startsWith('#') || target.startsWith('javascript:')) return;
                            e.preventDefault();
                            setTimeout(() => { window.location.href = target; }, 250);
                        }
                    });
                });
            }
        });

        // --- THEME & LANGUAGE LOGIC ---
        window.translationCache = {};

        async function translateGoogleAPI(text, targetLang) {
            if(targetLang === 'en') return text; 
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                let fullText = "";
                if (data && data[0]) {
                    for (let i = 0; i < data[0].length; i++) fullText += data[0][i][0];
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

                if (lang === 'en') {
                    if (el.tagName === 'P' || el.tagName === 'SPAN' || el.tagName === 'H1' || el.tagName === 'H3') {
                        el.innerText = originalText;
                    }
                    continue;
                }

                let cacheKey = `${lang}_${originalText}`;
                
                if(window.translationCache[cacheKey]) {
                    el.innerText = window.translationCache[cacheKey];
                } else {
                    let translation = await translateGoogleAPI(originalText, lang);
                    window.translationCache[cacheKey] = translation; 
                    el.innerText = translation;
                }
            }
        }

        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') { 
            bodyEl.classList.add('light-mode'); 
            themeBtn.innerHTML = '🌙 DARK MODE'; 
        }

        function applyLanguage(lang) {
            langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                const textSpan = el.querySelector('span:not(.dynamic-translation)');
                if (textSpan && !el.hasAttribute('data-en')) {
                    textSpan.innerHTML = el.getAttribute(`data-${lang}`);
                } else {
                    if (!el.classList.contains('dynamic-translation')) {
                        el.innerHTML = el.getAttribute(`data-${lang}`);
                    }
                }
            });

            processDynamicTranslations(lang);
        }

        themeBtn.addEventListener('click', () => {
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

        langBtn.addEventListener('click', () => {
            currentLang = currentLang === 'en' ? 'ms' : 'en';
            localStorage.setItem('shield_lang', currentLang);
            applyLanguage(currentLang);
        });

        // --- GAME LOGIC ---
        function updateStrikes() {
            for(let i = 1; i <= maxMistakes; i++) {
                const orb = document.getElementById(`strike-${i}`);
                if (i <= currentMistakes) {
                    orb.classList.add('lost');
                } else {
                    orb.classList.remove('lost');
                }
            }
        }

        function renderBoard(jumpDirection = 'none') {
            boardEl.innerHTML = '';
            
            for(let i = 0; i <= maxPos; i++) {
                let tile = document.createElement('div');
                tile.className = 'w-8 h-8 md:w-10 md:h-10 border rounded flex items-center justify-center text-xs md:text-sm font-bold transition-all duration-300 relative';
                
                if (i === currentPos) {
                    let bgClass = jumpDirection === 'backward' ? 'bg-red-600' : 'bg-purple-600';
                    let borderClass = jumpDirection === 'backward' ? 'border-red-400' : 'border-purple-400';
                    let shadowClass = jumpDirection === 'backward' ? 'shadow-[0_0_15px_rgba(239,68,68,0.8)]' : 'shadow-[0_0_15px_rgba(168,85,247,0.8)]';
                    
                    tile.classList.add(bgClass, borderClass, shadowClass, 'text-white', 'z-10');
                    
                    let animClass = '';
                    if(jumpDirection === 'forward') animClass = 'leap-forward';
                    else if(jumpDirection === 'backward') animClass = 'leap-backward';
                    else animClass = 'tile-animate';

                    tile.innerHTML = `<span class="text-xl md:text-2xl leading-none ${animClass}">♞</span>`;
                } else if (i === maxPos) {
                    tile.classList.add('bg-emerald-900/30', 'border-emerald-500', 'text-emerald-500', 'shadow-[inset_0_0_10px_rgba(16,185,129,0.4)]');
                    tile.innerHTML = '<span class="text-lg md:text-xl leading-none">★</span>';
                } else if (i < currentPos) {
                    tile.classList.add('bg-purple-900/40', 'border-purple-800/50', 'text-purple-700', 'opacity-60');
                    tile.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>';
                } else {
                    tile.classList.add('bg-black/50', 'border-gray-800', 'text-gray-700', 'light-mode:bg-white', 'light-mode:text-gray-400');
                    tile.innerHTML = i;
                }
                boardEl.appendChild(tile);
            }
        }

        // 🚨 VICTORY FUNCTION
        function triggerVictory() {
            document.getElementById('track-correct').value = trackCorrect;
            document.getElementById('track-incorrect').value = trackIncorrect;
            document.getElementById('track-wrong').value = JSON.stringify(trackWrongList);

            document.getElementById('success-overlay').classList.remove('hidden');
            document.getElementById('success-overlay').classList.add('flex');
        }

        async function loadQuestion(isLangSwap = false) {
            const currentQ = questions[currentIndex];
            
            if (currentLang === 'en') {
                statementEl.innerText = `"${currentQ.statement}"`;
            } else {
                statementEl.innerText = "Translating...";
                let cacheKey = `ms_q_${currentIndex}`;
                if(window.translationCache[cacheKey]) {
                    statementEl.innerText = `"${window.translationCache[cacheKey]}"`;
                } else {
                    let trans = await translateGoogleAPI(currentQ.statement, 'ms');
                    window.translationCache[cacheKey] = trans;
                    statementEl.innerText = `"${trans}"`;
                }
            }
            
            // Show them the actual progress through the array even if it looped
            roundEl.innerText = currentIndex + 1;
            totalEl.innerText = questions.length;
            
            if(!isLangSwap) {
                feedbackEl.style.opacity = "0";
                btnTrue.disabled = false;
                btnFalse.disabled = false;
                isProcessing = false;
                
                if(currentPos === 0) renderBoard('none');
            }
        }
function submitAnswer(userChoice) {
            if (isProcessing) return;
            isProcessing = true;

            btnTrue.disabled = true;
            btnFalse.disabled = true;

            const currentQ = questions[currentIndex];
            const isCorrectAnswer = parseInt(currentQ.is_true); 
            
            let stepsForward = parseInt(currentQ.steps_forward || 1);
            if (stepsForward > 2) stepsForward = 1; 
            let stepsBackward = parseInt(currentQ.steps_backward || 1);

            let oldPos = currentPos;
            let jumpDirection = 'none';

            if (userChoice === isCorrectAnswer) {
                trackCorrect++;
                if(sfxRight) { sfxRight.currentTime = 0; sfxRight.play().catch(()=>{}); }
                
                currentPos += stepsForward;
                if (currentPos > maxPos) currentPos = maxPos; 
                jumpDirection = currentPos > oldPos ? 'forward' : 'none';
                
                feedbackEl.innerText = currentLang === 'en' ? `[VALID] +${stepsForward} Steps Forward` : `[SAH] +${stepsForward} Langkah Ke Depan`;
                feedbackEl.className = "text-sm font-bold uppercase tracking-widest mt-4 h-5 text-emerald-400 opacity-100 transition-opacity drop-shadow-[0_0_5px_rgba(16,185,129,0.8)]";

            } else {
                trackIncorrect++;
                let userGuessedStr = userChoice === 1 ? 'TRUE' : 'FALSE';
                trackWrongList.push(`Statement: "${currentQ.statement}" | Guessed: ${userGuessedStr}`); 

                if(sfxWrong) { sfxWrong.currentTime = 0; sfxWrong.play().catch(()=>{}); }
                
                currentPos -= stepsBackward;
                if (currentPos < 0) currentPos = 0;
                jumpDirection = currentPos < oldPos ? 'backward' : 'none';
                
                currentMistakes++; // Fixed variable name
                updateStrikes();

                feedbackEl.innerText = currentLang === 'en' ? `[ERROR] -${stepsBackward} Steps Back` : `[RALAT] -${stepsBackward} Langkah Berundur`;
                feedbackEl.className = "text-sm font-bold uppercase tracking-widest mt-4 h-5 text-red-500 opacity-100 transition-opacity drop-shadow-[0_0_5px_rgba(239,68,68,0.8)]";
                
                document.getElementById('game-container').classList.add('shake');
                setTimeout(() => document.getElementById('game-container').classList.remove('shake'), 500);
            }

            renderBoard(jumpDirection);

            setTimeout(() => {
                if (currentPos >= maxPos) {
                    triggerVictory(); 
                    return;
                }

                // 🔥 SILENT LOGGER INJECTED HERE 🔥
                if (currentMistakes >= maxMistakes) {
                    setTimeout(() => {
                        document.getElementById('fail-overlay').classList.remove('hidden');
                        document.getElementById('fail-overlay').classList.add('flex');
                        try { window.parent.postMessage('playMusic', '*'); } catch(e) {}
                        
                        logArcadeFailure(); // Logs the failure instantly
                        
                    }, 1500);
                    return;
                }

                currentIndex = (currentIndex + 1) % questions.length;
                loadQuestion();
                
            }, 1000); 
        }

        function restartGame() {
            document.getElementById('fail-overlay').classList.add('hidden');
            document.getElementById('fail-overlay').classList.remove('flex');
            currentIndex = 0;
            currentPos = 0;
            
            currentMistakes = 0;
            updateStrikes();

            trackCorrect = 0; 
            trackIncorrect = 0; 
            trackWrongList = []; 

            loadQuestion();
        }

        // 🚨 FIRE EVERYTHING UP
        window.addEventListener('DOMContentLoaded', () => {
            applyLanguage(currentLang);
            if(questions.length > 0) {
                updateStrikes();
                loadQuestion();
            } else {
                statementEl.innerText = "ERROR: NO STATEMENTS FOUND IN MODULE.";
                statementEl.classList.add('text-red-500');
                totalEl.innerText = "0";
            }
        });

// 🔥 SILENT ARCADE LOGGER 🔥
    function logArcadeFailure() {
    fetch('{{ route('agent.log_failure') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ room_number: 6 }) 
    })
    .then(response => console.log("Arcade failure recorded."))
    .catch(error => console.error("Error logging failure:", error));
    }

    </script>
    @include('partials.cursor')
</body>
</html>