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
            
            --block-bg: rgba(88, 28, 135, 0.3);
            --block-border: rgba(168, 85, 247, 0.5);
            --block-text: #ffffff;
            --block-shadow: rgba(168, 85, 247, 0.3);
            
            --input-bg: #0a0a0a;
            --input-border: #a855f7;
            --input-text: #ffffff;
            --input-shadow: rgba(168,85,247,0.5);
        }

        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: rgba(147, 51, 234, 0.6);
            --title-color: #7e22ce; /* Purple 700 */
            --vid-filter: invert(1) hue-rotate(180deg) brightness(1.5);
            --vid-overlay: rgba(255, 255, 255, 0.5);

            --block-bg: rgba(243, 232, 255, 0.8);
            --block-border: rgba(147, 51, 234, 0.6);
            --block-text: #4c1d95;
            --block-shadow: rgba(147, 51, 234, 0.2);

            --input-bg: #ffffff;
            --input-border: #7e22ce;
            --input-text: #1f2937;
            --input-shadow: rgba(147, 51, 234, 0.3);
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
        .light-mode .text-purple-500 { color: #6b21a8 !important; }
        .light-mode .bg-black\/60 { background-color: rgba(255,255,255,0.85) !important; }
        .light-mode .bg-black\/80 { background-color: rgba(255,255,255,0.95) !important; }
        .light-mode .border-gray-800 { border-color: #d1d5db !important; }

        /* 🚨 HINT READABILITY FIXES FOR LIGHT MODE */
        .light-mode .text-yellow-400 { color: #92400e !important; font-weight: 800 !important; }
        .light-mode .text-yellow-500 { color: #b45309 !important; font-weight: 800 !important; }
        .light-mode .bg-yellow-900\/20 { background-color: #fef3c7 !important; border-color: #d97706 !important; }
        .light-mode .bg-yellow-900\/30 { background-color: #fde68a !important; border-color: #b45309 !important; }
        .light-mode .hover\:bg-yellow-600:hover { background-color: #d97706 !important; color: #ffffff !important; }

        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }
        
        .lz-input { 
            background-color: var(--input-bg); 
            border: 2px solid var(--input-border); 
            color: var(--input-text); 
            width: 100%; 
            padding: 15px; 
            border-radius: 8px; 
            font-size: 1.5rem; 
            text-align: center; 
            text-transform: uppercase; 
            transition: all 0.3s; 
            letter-spacing: 0.2em;
        }
        .lz-input:focus { outline: none; box-shadow: 0 0 20px var(--input-shadow); }
        
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; border-color: #ef4444 !important; box-shadow: 0 0 20px rgba(239,68,68,0.5) !important; color: #ef4444;}
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }

        /* NEW PARTITION TILE STYLING */
        .letter-block {
            display: inline-block;
            background-color: var(--block-bg);
            border: 1px solid var(--block-border);
            border-radius: 0.375rem; 
            padding: 0.5rem 1rem; 
            margin: 0.25rem; 
            font-size: 2.25rem; 
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--block-text);
            box-shadow: 0 0 10px var(--block-shadow);
            transition: all 0.3s ease;
        }
        .light-mode .letter-block { text-shadow: none; }
        @media (min-width: 768px) {
            .letter-block {
                font-size: 3rem; 
                padding: 0.75rem 1.25rem; 
            }
        }
        .space-block { display: inline-block; width: 1.5rem; }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden flex flex-col items-center justify-center p-4">

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
            <p class="text-[10px] md:text-sm text-purple-500/70 font-bold tracking-widest uppercase" data-en="Decryption Sequence Initiated" data-ms="Urutan Nyahsulit Dimulakan">Decryption Sequence Initiated</p>
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

    <div class="relative z-10 w-full max-w-3xl theme-card backdrop-blur-md rounded-xl p-6 md:p-8 shadow-[0_0_30px_rgba(147,51,234,0.2)] text-center mt-24 md:mt-16 mb-8" id="game-container">
        
        <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4 transition-colors">
            <div class="text-left">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" data-en="Progress" data-ms="Kemajuan">Progress</p>
                <p class="text-lg text-white font-bold font-mono"><span id="current-round">1</span> / <span id="total-rounds">X</span></p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" data-en="Max Reward" data-ms="Ganjaran Max">Max Reward</p>
                <p class="text-lg text-emerald-400 font-bold font-mono">{{ $game->base_score }} PTS</p>
            </div>
        </div>

        @if($game->instruction)
            <p class="text-sm text-gray-400 italic mb-8 px-4 dynamic-translation" data-original="{{ $game->instruction }}">"{{ $game->instruction }}"</p>
        @endif

        <div class="mb-10">
            <p class="text-[10px] theme-title uppercase tracking-widest font-bold mb-3" data-en="Encrypted Payload" data-ms="Muatan Disulitkan">Encrypted Payload</p>
            <div id="scrambled-display" class="flex flex-wrap justify-center items-center select-none min-h-[80px]">
                </div>
        </div>

        <div id="hint-container" class="mb-6 hidden">
            <p class="text-[10px] text-yellow-500 uppercase tracking-widest font-bold mb-1" data-en="Intercepted Clue" data-ms="Petunjuk Dipintas">Intercepted Clue</p>
            <p id="hint-display" class="text-sm text-yellow-400 italic bg-yellow-900/20 border border-yellow-500/30 p-2 rounded inline-block dynamic-translation" data-original=""></p>
        </div>

        <div class="mb-6 relative">
            <input type="text" id="answer-input" class="lz-input" placeholder="ENTER DECRYPTION KEY" data-en-placeholder="ENTER DECRYPTION KEY" data-ms-placeholder="MASUKKAN KUNCI NYAHSULIT" autocomplete="off" spellcheck="false">
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button id="hint-btn" onclick="showHint()" class="px-6 py-3 bg-yellow-900/30 border border-yellow-600/50 text-yellow-500 rounded font-bold tracking-widest uppercase text-sm hover:bg-yellow-600 hover:text-white transition-colors hidden">
                <span data-en="Request Hint" data-ms="Minta Petunjuk">Request Hint</span>
            </button>
            <button onclick="checkAnswer()" class="px-10 py-3 bg-purple-600 hover:bg-purple-500 text-white rounded font-bold tracking-widest uppercase text-lg shadow-[0_0_15px_rgba(168,85,247,0.5)] transition-all">
                <span data-en="DECRYPT" data-ms="NYAHSULIT">DECRYPT</span>
            </button>
        </div>

    </div>

    <div id="success-overlay" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center pointer-events-auto">
        <div class="w-24 h-24 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h2 class="title-font text-4xl text-emerald-400 mb-2 tracking-widest uppercase text-center" data-en="Payload Decrypted" data-ms="Muatan Dinyahsulit">Payload Decrypted</h2>
        <p class="text-gray-400 font-mono mb-8 text-center" data-en="All sequences cleared successfully." data-ms="Semua urutan berjaya diselesaikan.">All sequences cleared successfully.</p>
        
        <form action="{{ route('agent.arcade.complete', $game->id) }}" method="POST">
            @csrf
            <input type="hidden" name="score" value="{{ $game->base_score }}">
            <input type="hidden" name="correct" id="track-correct" value="0">
            <input type="hidden" name="incorrect" id="track-incorrect" value="0">
            
            <button type="submit" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-500 text-black rounded font-extrabold tracking-widest uppercase text-lg shadow-[0_0_20px_rgba(16,185,129,0.6)] transition-all">
                <span data-en="CLAIM REWARD & RETURN" data-ms="TUNTUT GANJARAN">CLAIM REWARD & RETURN</span>
            </button>
        </form>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>
    <audio id="sfx-right" src="{{ asset('audio/right-answer.mp3') }}" preload="auto"></audio>
    <audio id="sfx-wrong" src="{{ asset('audio/wrong-answer.mp3') }}" preload="auto"></audio>

    <script>
        // --- SOUND INTERCEPTOR ---
        document.addEventListener("DOMContentLoaded", function() {
            const clickSound = document.getElementById('ui-click-sound');
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
        const inputEl = document.getElementById('answer-input');

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

            if (inputEl && inputEl.hasAttribute(`data-${lang}-placeholder`)) {
                inputEl.placeholder = inputEl.getAttribute(`data-${lang}-placeholder`);
            }

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
        const gameData = @json($game->game_data);
        
        let words = [];
        if (gameData && gameData.words) {
            let rawWords = Array.isArray(gameData.words) ? gameData.words : Object.values(gameData.words);
            words = rawWords.map(item => {
                if (typeof item === 'string') {
                    return { word: item, hint: '' };
                } else if (typeof item === 'object' && item !== null && item.word) {
                    return { word: item.word, hint: item.hint || '' };
                }
                return null;
            }).filter(item => item !== null && item.word.trim() !== '');
        } else if (gameData && gameData.target_word) {
            words.push({ word: gameData.target_word, hint: '' });
        }
        
        let currentWordIndex = 0;
        
        // 🚨 NEW TRACKING VARIABLES
        let trackCorrect = 0;
        let trackIncorrect = 0;
        
        const displayEl = document.getElementById('scrambled-display');
        const roundEl = document.getElementById('current-round');
        const totalEl = document.getElementById('total-rounds');
        const hintBtn = document.getElementById('hint-btn');
        const hintContainer = document.getElementById('hint-container');
        const hintDisplay = document.getElementById('hint-display');
        
        const sfxRight = document.getElementById('sfx-right');
        const sfxWrong = document.getElementById('sfx-wrong');

        function playClick() {
            const clickSound = document.getElementById('ui-click-sound');
            if(clickSound) {
                clickSound.currentTime = 0;
                clickSound.play().catch(e => console.log("Sound blocked:", e));
            }
        }

        // UPGRADED SCRAMBLE LOGIC: Respects spaces!
        function scrambleWord(word) {
            let chars = word.split('');
            let nonSpaceIndices = [];
            let nonSpaceChars = [];
            
            for (let i = 0; i < chars.length; i++) {
                if (chars[i] !== ' ') {
                    nonSpaceIndices.push(i);
                    nonSpaceChars.push(chars[i]);
                }
            }
            
            let scrambledChars = [...nonSpaceChars];
            let attempts = 0;
            
            if (nonSpaceChars.length > 2) {
                while (scrambledChars.join('') === nonSpaceChars.join('') && attempts < 10) {
                    for (let i = scrambledChars.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [scrambledChars[i], scrambledChars[j]] = [scrambledChars[j], scrambledChars[i]];
                    }
                    attempts++;
                }
            }
            
            let result = [...chars];
            for (let i = 0; i < nonSpaceIndices.length; i++) {
                result[nonSpaceIndices[i]] = scrambledChars[i];
            }
            
            return result;
        }

        // 🚨 VICTORY OVERLAY TRIGGER
        function triggerVictory() {
            document.getElementById('track-correct').value = trackCorrect;
            document.getElementById('track-incorrect').value = trackIncorrect;
            
            document.getElementById('success-overlay').classList.remove('hidden');
            document.getElementById('success-overlay').classList.add('flex');
        }

        function loadWord() {
            if (currentWordIndex >= words.length) {
                triggerVictory();
                return;
            }

            const currentObj = words[currentWordIndex];
            const wordToGuess = currentObj.word.toUpperCase();
            
            roundEl.innerText = currentWordIndex + 1;
            totalEl.innerText = words.length;
            
            // Generate the Partition Tiles!
            const scrambledArr = scrambleWord(wordToGuess);
            displayEl.innerHTML = '';
            for (let i = 0; i < scrambledArr.length; i++) {
                if (scrambledArr[i] === ' ') {
                    displayEl.innerHTML += `<span class="space-block"></span>`;
                } else {
                    displayEl.innerHTML += `<span class="letter-block">${scrambledArr[i]}</span>`;
                }
            }
            
            inputEl.value = '';
            inputEl.focus();
            hintContainer.classList.add('hidden');
            
            if (currentObj.hint && currentObj.hint.trim() !== '') {
                hintBtn.classList.remove('hidden');
                
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
                hintDisplay.setAttribute('data-original', '');
            }
        }

        function showHint() {
            playClick();
            hintContainer.classList.remove('hidden');
            hintBtn.classList.add('hidden');
        }

        let scrambleMistakes = 0; // Local counter for mistakes on a single word

        function checkAnswer() {
            playClick();
            const currentObj = words[currentWordIndex];
            const correctWord = currentObj.word.toUpperCase().replace(/\s+/g, ' ').trim();
            const userGuess = inputEl.value.toUpperCase().replace(/\s+/g, ' ').trim();

            if (userGuess === correctWord) {
                trackCorrect++; // 🚨 Tracking Check
                scrambleMistakes = 0; // Reset mistakes on success
                
                if(sfxRight) { sfxRight.currentTime = 0; sfxRight.play().catch(e => console.log("Sound blocked:", e)); }
                
                inputEl.style.borderColor = '#10b981';
                inputEl.style.color = '#10b981';
                setTimeout(() => {
                    inputEl.style.borderColor = '';
                    inputEl.style.color = '';
                    currentWordIndex++;
                    loadWord();
                }, 600);
            } else {
                trackIncorrect++; // 🚨 Tracking Check
                scrambleMistakes++; // Increment local mistakes
                
                if(sfxWrong) { sfxWrong.currentTime = 0; sfxWrong.play().catch(e => console.log("Sound blocked:", e)); }
                
                inputEl.classList.add('shake');
                setTimeout(() => {
                    inputEl.classList.remove('shake');
                }, 600);
                
                // If they fail 3 times on the same word, trigger the fail state
                if (scrambleMistakes >= 3) {
                    setTimeout(() => {
                        document.getElementById('fail-overlay').classList.remove('hidden');
                        document.getElementById('fail-overlay').classList.add('flex');
                        
                        // 🔥 SILENT ARCADE LOGGER TRIGGERED HERE 🔥
                        logArcadeFailure();
                    }, 1000);
                }
            }
        }

        inputEl.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                checkAnswer();
            }
        });

        // Trigger translations immediately on initial load
        window.addEventListener('DOMContentLoaded', () => {
            applyLanguage(currentLang);
            if(words.length > 0) {
                loadWord();
            } else {
                displayEl.innerHTML = `<span class="text-red-500 font-bold text-2xl">ERROR: NO WORDS FOUND</span>`;
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