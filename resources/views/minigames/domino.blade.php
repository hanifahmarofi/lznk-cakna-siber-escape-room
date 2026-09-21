<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // {{ $game->title }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        /* 🔥 HIDE DEFAULT CURSOR IN IFRAME 🔥 */
        body, a, button, input, select, textarea {
            cursor: none !important;
        }

        /* --- HIGH CONTRAST THEME SYSTEM --- */
        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --card-bg: rgba(21, 21, 21, 0.85);
            --border-color: rgba(168, 85, 247, 0.5);
            --title-color: #a855f7; /* Purple 500 */
            --key-bg: rgba(88, 28, 135, 0.3);
            --key-hover: rgba(168, 85, 247, 0.8);
            --vid-filter: none;
            --vid-overlay: rgba(88, 28, 135, 0.2);
        }

        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: rgba(147, 51, 234, 0.6);
            --title-color: #7e22ce; /* Purple 700 */
            --key-bg: rgba(243, 232, 255, 0.8);
            --key-hover: rgba(147, 51, 234, 0.9);
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
        .light-mode .bg-black\/60 { background-color: rgba(255,255,255,0.85) !important; }
        .light-mode .bg-black\/40 { background-color: rgba(243,244,246,0.8) !important; }
        .light-mode .border-gray-800 { border-color: #d1d5db !important; }
        .light-mode .phrase-tile { border-bottom-color: #7e22ce !important; color: #000 !important; text-shadow: none !important; }

        /* 🚨 HINT READABILITY FIXES FOR LIGHT MODE */
        .light-mode .text-yellow-400 { color: #92400e !important; font-weight: 800 !important; }
        .light-mode .text-yellow-500 { color: #b45309 !important; font-weight: 800 !important; }
        .light-mode .bg-yellow-900\/20 { background-color: #fef3c7 !important; border-color: #d97706 !important; }
        .light-mode .bg-yellow-900\/30 { background-color: #fde68a !important; border-color: #b45309 !important; }
        .light-mode .hover\:bg-yellow-600:hover { background-color: #d97706 !important; color: #ffffff !important; }

        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }
        
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }

        /* 🚨 BULLETPROOF WORD SPACING WRAPPER 🚨 */
        .word-wrapper {
            display: flex;
            flex-wrap: nowrap;
            margin: 0.5rem 1rem; /* Forces a wide physical gap between words */
        }
        @media (min-width: 768px) {
            .word-wrapper {
                margin: 0.5rem 1.5rem; /* Even wider gap on PC */
            }
        }

        /* HANGMAN TILES */
        .phrase-tile {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 3rem;
            margin: 0.15rem;
            border-bottom: 3px solid rgba(168, 85, 247, 0.8);
            font-size: 1.5rem;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            text-transform: uppercase;
            text-shadow: 0 0 10px rgba(168,85,247,0.8);
            transition: all 0.3s ease;
        }
        @media (min-width: 768px) {
            .phrase-tile { width: 3rem; height: 4rem; font-size: 2rem; margin: 0.25rem; }
        }

        /* QWERTY KEYBOARD STYLING */
        .key-btn {
            background-color: var(--key-bg);
            border: 1px solid var(--border-color);
            color: var(--title-color);
            border-radius: 0.375rem;
            font-family: 'Share Tech Mono', monospace;
            font-weight: bold;
            transition: all 0.2s;
            cursor: pointer;
        }
        .key-btn:hover:not(:disabled) {
            background-color: var(--key-hover);
            color: white;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.6);
            transform: translateY(-2px);
        }
        .key-btn:disabled.correct {
            background-color: rgba(16, 185, 129, 0.2) !important;
            border-color: #10b981 !important;
            color: #10b981 !important;
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.2) !important;
        }
        .key-btn:disabled.wrong {
            background-color: rgba(239, 68, 68, 0.2) !important;
            border-color: #ef4444 !important;
            color: #ef4444 !important;
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* DOMINO LIVES STYLING */
        .domino-block {
            width: 1.5rem; height: 2.5rem;
            background-color: #a855f7;
            border-radius: 0.25rem;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.5), 0 0 10px rgba(168,85,247,0.5);
            transition: all 0.5s ease;
        }
        @media (min-width: 768px) {
            .domino-block { width: 2rem; height: 3rem; }
        }
        .domino-block.fallen {
            background-color: #374151;
            box-shadow: none;
            transform: rotate(75deg) translateY(10px);
            opacity: 0.3;
        }
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
            <p class="text-[10px] md:text-sm text-purple-500/70 font-bold tracking-widest uppercase" data-en="Domino Protocol Initiated" data-ms="Protokol Domino Dimulakan">Domino Protocol Initiated</p>
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

    <div class="relative z-10 w-full max-w-4xl theme-card backdrop-blur-md rounded-xl p-6 md:p-8 shadow-[0_0_30px_rgba(147,51,234,0.2)] text-center mt-28 md:mt-32 mb-8" id="game-container">
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8 md:mb-10 border-b border-gray-800 pb-6 md:pb-8 transition-colors">
            <div class="text-center md:text-left flex md:block w-full md:w-auto justify-between">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold text-left" data-en="Progress" data-ms="Kemajuan">Progress</p>
                <p class="text-lg md:text-2xl text-white font-bold font-mono"><span id="current-round">1</span> / <span id="total-rounds">X</span></p>
            </div>
            
            <div class="flex flex-col items-center">
                <p class="text-[10px] theme-title uppercase tracking-widest font-bold mb-3" data-en="System Integrity" data-ms="Integriti Sistem">System Integrity</p>
                <div class="flex gap-2 md:gap-3" id="lives-container">
                    </div>
            </div>

            <div class="text-center md:text-right flex md:block w-full md:w-auto justify-between">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold text-left md:text-right" data-en="Max Reward" data-ms="Ganjaran Max">Max Reward</p>
                <p class="text-lg md:text-2xl text-emerald-400 font-bold font-mono">{{ $game->base_score }} PTS</p>
            </div>
        </div>

        @if($game->instruction)
            <p class="text-sm md:text-base text-gray-400 italic mb-8 px-4 dynamic-translation" data-original="{{ $game->instruction }}">"{{ $game->instruction }}"</p>
        @endif

        <div id="hint-container" class="mb-6 hidden">
            <p class="text-sm md:text-base text-yellow-400 italic bg-yellow-900/20 border border-yellow-500/30 p-3 rounded inline-block dynamic-translation" id="hint-display" data-original=""></p>
        </div>

        <button id="hint-btn" onclick="showHint()" class="mb-8 px-6 py-2 bg-yellow-900/30 border border-yellow-600/50 text-yellow-500 rounded font-bold tracking-widest uppercase text-sm hover:bg-yellow-600 hover:text-white transition-colors hidden mx-auto">
            <span data-en="Request Hint" data-ms="Minta Petunjuk">Request Hint</span>
        </button>

        <div class="mb-10 md:mb-12">
            <div id="phrase-display" class="flex flex-wrap justify-center items-center select-none min-h-[80px] md:min-h-[100px]">
                </div>
        </div>

        <div id="keyboard" class="flex flex-col gap-2 md:gap-3 max-w-3xl mx-auto w-full items-center px-1">
            </div>

    </div>

    <div id="success-overlay" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center p-4 pointer-events-auto">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h2 class="title-font text-3xl md:text-4xl text-emerald-400 mb-2 tracking-widest uppercase text-center" data-en="Protocol Complete" data-ms="Protokol Selesai">Protocol Complete</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="All firewall targets identified." data-ms="Semua sasaran tembok api dikenal pasti.">All firewall targets identified.</p>
        
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
        <h2 class="title-font text-3xl md:text-4xl text-red-500 mb-2 tracking-widest uppercase text-center" data-en="System Integrity Failed" data-ms="Integriti Sistem Gagal">System Integrity Failed</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="You ran out of attempts." data-ms="Anda kehabisan percubaan.">You ran out of attempts.</p>
        
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
        const maxMistakes = gameData.max_mistakes ? parseInt(gameData.max_mistakes) : 6;
        let isGameOver = false; // 🔥 HARD STOP FLAG
        
        let phrases = [];
        if (gameData && gameData.phrases) {
            let rawPhrases = Array.isArray(gameData.phrases) ? gameData.phrases : Object.values(gameData.phrases);
            phrases = rawPhrases.map(item => {
                if (typeof item === 'string') return { phrase: item, hint: '' };
                else if (typeof item === 'object' && item !== null && item.phrase) return { phrase: item.phrase, hint: item.hint || '' };
                return null;
            }).filter(item => item !== null && item.phrase.trim() !== '');
        } else if (gameData && gameData.target_phrase) {
            phrases.push({ phrase: gameData.target_phrase, hint: '' });
        }

        let currentPhraseIndex = 0;
        let currentMistakes = 0;
        let guessedLetters = new Set();
        let targetWord = "";

        // 🚨 TRACKING VARIABLES
        let trackCorrect = 0;
        let trackIncorrect = 0;
        let trackWrongList = [];

        // UI Elements
        const displayEl = document.getElementById('phrase-display');
        const keyboardEl = document.getElementById('keyboard');
        const livesEl = document.getElementById('lives-container');
        const hintBtn = document.getElementById('hint-btn');
        const hintContainer = document.getElementById('hint-container');
        const hintDisplay = document.getElementById('hint-display');
        const roundEl = document.getElementById('current-round');
        const totalEl = document.getElementById('total-rounds');
        
        const clickSound = document.getElementById('ui-click-sound');
        const sfxRight = document.getElementById('sfx-right');
        const sfxWrong = document.getElementById('sfx-wrong');

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
                            
                            if (this.getAttribute('target') === '_blank') {
                                setTimeout(() => { window.open(target, '_blank'); }, 250);
                            } else {
                                setTimeout(() => { window.location.href = target; }, 250);
                            }
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
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; 
        }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
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

        // --- GAME LOGIC FUNCTIONS ---
        function initKeyboard() {
            keyboardEl.innerHTML = '';
            
            const rows = [
                "QWERTYUIOP".split(''),
                "ASDFGHJKL".split(''),
                "ZXCVBNM".split('')
            ];

            rows.forEach(row => {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'flex justify-center gap-1.5 md:gap-2 w-full';
                
                row.forEach(letter => {
                    const btn = document.createElement('button');
                    btn.className = 'key-btn flex-1 max-w-[36px] sm:max-w-[45px] md:max-w-[55px] h-10 sm:h-12 md:h-14 flex items-center justify-center text-sm md:text-lg shadow-[0_0_8px_rgba(168,85,247,0.2)]';
                    btn.innerText = letter;
                    btn.id = `key-${letter}`;
                    btn.onclick = () => handleGuess(letter);
                    rowDiv.appendChild(btn);
                });
                
                keyboardEl.appendChild(rowDiv);
            });
        }

        // 🔥 FIX: DOMINO BLOCK IDS NORMALIZED TO 0, 1, 2... 🔥
        function initLives() {
            livesEl.innerHTML = '';
            for(let i = 0; i < maxMistakes; i++) {
                const block = document.createElement('div');
                block.className = 'domino-block';
                block.id = `domino-${i}`;
                livesEl.appendChild(block);
            }
        }

        // 🔥 FIX: CORRECTLY TARGET THE DOMINO BASED ON MISTAKES 🔥
        function updateLivesDisplay() {
            if (currentMistakes > 0 && currentMistakes <= maxMistakes) {
                const block = document.getElementById(`domino-${currentMistakes - 1}`);
                if (block) block.classList.add('fallen');
            }
        }

        // 🚨 UPGRADED WORD-WRAP LOGIC 🚨
        function renderPhrase() {
            displayEl.innerHTML = '';
            let won = true;
            
            // Split the phrase into whole words
            const wordsArray = targetWord.split(' ');
            
            wordsArray.forEach(word => {
                // Wrap each word in a custom CSS class to enforce physical gaps and nowrap
                let wordDiv = document.createElement('div');
                wordDiv.className = 'word-wrapper'; 
                
                for(let i = 0; i < word.length; i++) {
                    const char = word[i];
                    if (guessedLetters.has(char)) {
                        wordDiv.innerHTML += `<span class="phrase-tile border-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] text-emerald-400">${char}</span>`;
                    } else {
                        wordDiv.innerHTML += `<span class="phrase-tile"></span>`;
                        won = false;
                    }
                }
                
                displayEl.appendChild(wordDiv);
            });
            
            return won;
        }

        // 🚨 NEW VICTORY FUNCTION
        function triggerVictory() {
            document.getElementById('track-correct').value = trackCorrect;
            document.getElementById('track-incorrect').value = trackIncorrect;
            document.getElementById('track-wrong').value = JSON.stringify(trackWrongList);

            document.getElementById('success-overlay').classList.remove('hidden');
            document.getElementById('success-overlay').classList.add('flex');
        }

        // 🔥 FIX: HARD STOP ADDED IF GAME IS OVER 🔥
        function handleGuess(letter) {
            if (isGameOver || guessedLetters.has(letter) || currentMistakes >= maxMistakes) return;
            
            guessedLetters.add(letter);
            const btn = document.getElementById(`key-${letter}`);
            
            if (targetWord.includes(letter)) {
                trackCorrect++; 

                if(sfxRight) { sfxRight.currentTime = 0; sfxRight.play().catch(()=>{}); }
                btn.disabled = true;
                btn.classList.add('correct');
                
                const won = renderPhrase();
                if (won) {
                    isGameOver = true; // Lock game momentarily
                    setTimeout(() => {
                        currentPhraseIndex++;
                        isGameOver = false; // Unlock for next phrase
                        loadNextPhrase();
                    }, 1000);
                }
            } else {
                trackIncorrect++; 
                trackWrongList.push(`Phrase: ${targetWord} | Guessed: ${letter}`);

                if(sfxWrong) { sfxWrong.currentTime = 0; sfxWrong.play().catch(()=>{}); }
                document.getElementById('game-container').classList.add('shake');
                setTimeout(() => document.getElementById('game-container').classList.remove('shake'), 500);
                
                btn.disabled = true;
                btn.classList.add('wrong');
                
                currentMistakes++;
                updateLivesDisplay(); // 🔥 Fixed Function Called Here
                
                // 🔥 SILENT LOGGER INJECTED HERE 🔥
                if (currentMistakes >= maxMistakes) {
                    isGameOver = true; // 🚨 Lock game completely
                    setTimeout(() => {
                        document.getElementById('fail-overlay').classList.remove('hidden');
                        document.getElementById('fail-overlay').classList.add('flex');
                        
                        logArcadeFailure(); // Logs the failure instantly
                        
                    }, 800);
                }
            }
        }

        function loadNextPhrase() {
            if (currentPhraseIndex >= phrases.length) {
                isGameOver = true;
                triggerVictory(); // 🚨 Call Victory
                return;
            }

            const currentObj = phrases[currentPhraseIndex];
            targetWord = currentObj.phrase.toUpperCase();
            guessedLetters.clear();
            
            roundEl.innerText = currentPhraseIndex + 1;
            totalEl.innerText = phrases.length;
            
            if (currentObj.hint && currentObj.hint.trim() !== '') {
                hintBtn.classList.remove('hidden');
                hintContainer.classList.add('hidden');
                
                hintDisplay.setAttribute('data-original', currentObj.hint);
                if(currentLang === 'ms') {
                    hintDisplay.innerText = "Translating...";
                    translateGoogleAPI(currentObj.hint, 'ms').then(res => {
                        hintDisplay.innerText = res;
                        window.translationCache[`ms_${currentObj.hint}`] = res;
                    });
                } else {
                    hintDisplay.innerText = currentObj.hint;
                }
            } else {
                hintBtn.classList.add('hidden');
                hintContainer.classList.add('hidden');
                hintDisplay.setAttribute('data-original', '');
            }

            initKeyboard();
            renderPhrase();
        }

        function showHint() {
            playClick();
            hintContainer.classList.remove('hidden');
            hintBtn.classList.add('hidden');
        }

        // 🔥 FIX: RESET isGameOver FLAG ON RESTART 🔥
        function restartGame() {
            playClick();
            document.getElementById('fail-overlay').classList.add('hidden');
            document.getElementById('fail-overlay').classList.remove('flex');
            
            isGameOver = false; // 🚨 Unlock game
            currentPhraseIndex = 0;
            currentMistakes = 0;
            trackCorrect = 0; 
            trackIncorrect = 0; 
            trackWrongList = []; 

            initLives();
            loadNextPhrase();
        }

        document.addEventListener('keydown', (e) => {
            if(!document.getElementById('fail-overlay').classList.contains('hidden') || 
               !document.getElementById('success-overlay').classList.contains('hidden')) return;

            const letter = e.key.toUpperCase();
            if (/^[A-Z]$/.test(letter)) {
                handleGuess(letter);
            }
        });

        // 🚨 FIRE EVERYTHING UP
        window.addEventListener('DOMContentLoaded', () => {
            applyLanguage(currentLang);
            
            if(phrases.length > 0) {
                initLives();
                loadNextPhrase();
            } else {
                displayEl.innerHTML = `<span class="text-red-500 font-bold text-2xl">ERROR: NO PHRASES FOUND</span>`;
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