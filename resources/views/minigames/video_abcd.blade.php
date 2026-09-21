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
            
            --btn-bg: rgba(88, 28, 135, 0.2);
            --btn-border: rgba(168, 85, 247, 0.4);
            --btn-hover-bg: rgba(168, 85, 247, 0.4);
            --btn-hover-border: rgba(168, 85, 247, 0.8);
            --btn-text: #e9d5ff;
            --letter-bg: rgba(168, 85, 247, 0.2);
            --letter-border: #a855f7;
            --letter-text: #a855f7;
        }

        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: rgba(147, 51, 234, 0.6);
            --title-color: #7e22ce; /* Purple 700 */
            --vid-filter: invert(1) hue-rotate(180deg) brightness(1.5);
            --vid-overlay: rgba(255, 255, 255, 0.5);

            --btn-bg: rgba(243, 232, 255, 0.8);
            --btn-border: rgba(147, 51, 234, 0.6);
            --btn-hover-bg: rgba(233, 213, 255, 0.9);
            --btn-hover-border: rgba(126, 34, 206, 0.8);
            --btn-text: #4c1d95;
            --letter-bg: rgba(147, 51, 234, 0.1);
            --letter-border: #7e22ce;
            --letter-text: #7e22ce;
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
        .light-mode .text-red-500 { color: #dc2626 !important; }
        .light-mode .bg-black\/60 { background-color: rgba(255,255,255,0.85) !important; }
        .light-mode .bg-black\/50 { background-color: rgba(243,244,246,0.8) !important; }
        .light-mode .bg-black\/80 { background-color: rgba(255,255,255,0.95) !important; }
        .light-mode .bg-red-900\/10 { background-color: rgba(254,226,226,0.8) !important; }
        .light-mode .border-gray-800 { border-color: #d1d5db !important; }
        .light-mode .border-red-900\/30 { border-color: #f87171 !important; }

        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }
        
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }

        /* OPTION BUTTONS */
        .option-btn {
            background-color: var(--btn-bg);
            border: 2px solid var(--btn-border);
            color: var(--btn-text);
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            text-align: left;
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }
        .option-btn:hover:not(:disabled) {
            background-color: var(--btn-hover-bg);
            border-color: var(--btn-hover-border);
            box-shadow: 0 0 15px rgba(168,85,247,0.5);
            transform: translateY(-2px);
        }
        .option-btn:disabled { cursor: not-allowed; transform: none !important; }
        
        .option-letter {
            background-color: var(--letter-bg);
            color: var(--letter-text);
            border: 2px solid var(--letter-border);
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        /* STATE CLASSES */
        .option-btn.correct {
            background-color: rgba(16, 185, 129, 0.2) !important;
            border-color: #10b981 !important;
            color: #10b981 !important;
            box-shadow: inset 0 0 15px rgba(16, 185, 129, 0.3), 0 0 15px rgba(16, 185, 129, 0.5) !important;
        }
        .option-btn.correct .option-letter {
            background-color: #10b981 !important;
            color: #000 !important;
            border-color: #10b981 !important;
        }

        .option-btn.wrong {
            background-color: rgba(239, 68, 68, 0.2) !important;
            border-color: #ef4444 !important;
            color: #ef4444 !important;
            opacity: 0.7;
        }
        .option-btn.wrong .option-letter {
            background-color: #ef4444 !important;
            color: #000 !important;
            border-color: #ef4444 !important;
        }

        /* VIDEO CONTAINER */
        .video-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid var(--border-color);
            box-shadow: 0 0 20px rgba(168,85,247,0.2);
            background-color: #000;
        }
        .video-wrapper iframe, .video-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
            <h1 class="title-font text-xl md:text-3xl theme-title uppercase drop-shadow-[0_0_10px_rgba(168,85,247,0.5)] dynamic-translation" data-original="{{ $game->title }}">{{ $game->title }}</h1>
            <p class="text-[10px] md:text-sm text-purple-500/70 font-bold tracking-widest uppercase" data-en="Video Analysis Initiated" data-ms="Analisis Video Dimulakan">Video Analysis Initiated</p>
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

            <a href="{{ route('agent.arcade') }}" onclick="abortMission()" class="px-5 py-2 theme-card border border-purple-500/50 theme-title rounded hover:bg-purple-600 hover:text-white transition-all text-[10px] md:text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(147,51,234,0.3)] flex items-center justify-center gap-2">
                <span data-en="ABORT" data-ms="BATAL">ABORT</span>
            </a>
        </div>
    </div>

    <div class="relative z-10 w-full max-w-5xl theme-card backdrop-blur-md rounded-xl p-6 md:p-8 shadow-[0_0_30px_rgba(147,51,234,0.2)] text-center mt-24 md:mt-24 mb-8 flex flex-col md:flex-row gap-8 items-start" id="game-container">
        
        <div class="w-full md:w-1/2 flex flex-col gap-6">
            
            <div class="flex justify-between items-center border-b border-gray-800 pb-4 transition-colors">
                <div class="text-left">
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" data-en="Progress" data-ms="Kemajuan">Progress</p>
                    <p class="text-lg text-white font-bold font-mono"><span id="current-round">1</span> / <span id="total-rounds">X</span></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold" data-en="Max Reward" data-ms="Ganjaran Max">Max Reward</p>
                    <p class="text-lg text-emerald-400 font-bold font-mono">{{ $game->base_score }} PTS</p>
                </div>
            </div>

            <div class="w-full" id="video-render-target"></div>

            @if($game->instruction)
                <p class="text-sm text-gray-400 italic text-left bg-black/50 p-4 rounded border border-gray-800 dynamic-translation transition-colors" data-original="{{ $game->instruction }}">"{{ $game->instruction }}"</p>
            @endif
        </div>

        <div class="w-full md:w-1/2 flex flex-col h-full">
            
            <div class="flex justify-between items-center mb-6 bg-red-900/10 border border-red-900/30 p-3 rounded-lg transition-colors">
                <span class="text-xs text-red-500 font-bold tracking-widest uppercase" data-en="System Strikes" data-ms="Ralat Sistem">System Strikes</span>
                <div class="flex gap-2" id="strikes-container">
                    <div class="strike-orb" id="strike-1"></div>
                    <div class="strike-orb" id="strike-2"></div>
                    <div class="strike-orb" id="strike-3"></div>
                </div>
            </div>

            <div class="mb-6 text-left">
                <p class="text-[10px] theme-title uppercase tracking-widest font-bold mb-2" data-en="Surveillance Query:" data-ms="Pertanyaan Pemantauan:">Surveillance Query:</p>
                <h3 id="question-text" class="text-lg md:text-xl text-white font-bold leading-relaxed">LOADING QUERY...</h3>
            </div>

            <div class="flex flex-col gap-3 w-full" id="options-container">
                </div>

            <p id="feedback-display" class="text-sm font-bold uppercase tracking-widest mt-6 opacity-0 transition-opacity h-5 text-center"></p>

        </div>
    </div>

    <div id="success-overlay" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center p-4 pointer-events-auto">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h2 class="title-font text-3xl md:text-4xl text-emerald-400 mb-2 tracking-widest uppercase text-center" data-en="Analysis Complete" data-ms="Analisis Selesai">Analysis Complete</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="Video surveillance queries answered successfully." data-ms="Pertanyaan pemantauan video berjaya dijawab.">Video surveillance queries answered successfully.</p>
        
        <form action="{{ route('agent.arcade.complete', $game->id) }}" method="POST">
            @csrf
            <input type="hidden" name="score" value="{{ $game->base_score }}">
            <input type="hidden" name="correct" id="track-correct" value="0">
            <input type="hidden" name="incorrect" id="track-incorrect" value="0">
            <input type="hidden" name="wrong_answers" id="track-wrong" value="[]">
            
            <button type="submit" onclick="finishMission()" class="px-8 py-3 md:px-10 md:py-4 bg-emerald-600 hover:bg-emerald-500 text-black rounded font-extrabold tracking-widest uppercase text-base md:text-lg shadow-[0_0_20px_rgba(16,185,129,0.6)] transition-all">
                <span data-en="CLAIM REWARD & RETURN" data-ms="TUNTUT GANJARAN">CLAIM REWARD & RETURN</span>
            </button>
        </form>
    </div>

    <div id="fail-overlay" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center p-4 pointer-events-auto">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-red-900/50 flex items-center justify-center text-red-500 border border-red-500 mb-6 shadow-[0_0_30px_rgba(239,68,68,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </div>
        <h2 class="title-font text-3xl md:text-4xl text-red-500 mb-2 tracking-widest uppercase text-center" data-en="Analysis Failed" data-ms="Analisis Gagal">Analysis Failed</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="Maximum strike limit reached. Reboot required." data-ms="Had ralat maksimum dicapai. Mula semula diperlukan.">Maximum strike limit reached. Reboot required.</p>
        
        <button onclick="restartGame()" class="px-8 py-3 md:px-10 md:py-4 bg-red-600 hover:bg-red-500 text-white rounded font-extrabold tracking-widest uppercase text-base md:text-lg shadow-[0_0_20px_rgba(239,68,68,0.6)] transition-all">
            <span data-en="REBOOT SYSTEM" data-ms="MULA SEMULA SISTEM">REBOOT SYSTEM</span>
        </button>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>
    <audio id="sfx-right" src="{{ asset('audio/right-answer.mp3') }}" preload="auto"></audio>
    <audio id="sfx-wrong" src="{{ asset('audio/wrong-answer.mp3') }}" preload="auto"></audio>

    <script>
        const gameData = @json($game->game_data);
        
        let questions = [];
        if (gameData && gameData.questions) {
            let rawQ = Array.isArray(gameData.questions) ? gameData.questions : Object.values(gameData.questions);
            questions = rawQ.filter(q => q && q.question && q.question.trim() !== '');
        }

        let currentIndex = 0;
        let strikes = 0;
        const maxStrikes = 3;
        let isProcessing = false;
        
        let trackCorrect = 0;
        let trackIncorrect = 0;
        let trackWrongList = [];

        const videoTarget = document.getElementById('video-render-target');
        let currentVideoUrl = gameData.video_url || null; 
        let lastRenderedUrl = null;

        const questionEl = document.getElementById('question-text');
        const optionsEl = document.getElementById('options-container');
        const feedbackEl = document.getElementById('feedback-display');
        const roundEl = document.getElementById('current-round');
        const totalEl = document.getElementById('total-rounds');
        
        const clickSound = document.getElementById('ui-click-sound');
        const sfxRight = document.getElementById('sfx-right');
        const sfxWrong = document.getElementById('sfx-wrong');

        function playClick() { if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); } }

        // 🔥 SEND MESSAGE TO PARENT TO MUTE BACKGROUND MUSIC ON ABORT OR FINISH 🔥
        function abortMission() {
            playClick();
            try { window.parent.postMessage('playMusic', '*'); } catch(e) {}
        }
        
        function finishMission() {
            playClick();
            try { window.parent.postMessage('playMusic', '*'); } catch(e) {}
        }

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

        if(themeBtn) {
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
        }

        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        function updateStrikes() {
            for(let i = 1; i <= maxStrikes; i++) {
                const orb = document.getElementById(`strike-${i}`);
                if (i <= strikes) {
                    orb.classList.add('lost');
                } else {
                    orb.classList.remove('lost');
                }
            }
        }

        function renderVideo(vUrl) {
            if (!vUrl || vUrl.trim() === '') {
                if (!lastRenderedUrl) {
                    videoTarget.innerHTML = `<div class="w-full bg-red-900/30 border border-red-500 text-red-400 p-8 rounded text-center text-sm font-bold uppercase tracking-widest">VIDEO SOURCE NOT FOUND</div>`;
                }
                return;
            }

            if (vUrl === lastRenderedUrl) return; 

            lastRenderedUrl = vUrl;
            let processedUrl = vUrl;
            
            // 🔥 MUTE THE WEBSITE'S BACKGROUND MUSIC 🔥
            try { window.parent.postMessage('pauseMusic', '*'); } catch(e) {}
            try { window.parent.postMessage('muteMusic', '*'); } catch(e) {}

            const ytMatch = processedUrl.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);

            if (ytMatch && ytMatch[1]) {
                const videoId = ytMatch[1];
                // 🔥 REMOVED "&mute=1" TO FORCE YOUTUBE SOUND 🔥
                processedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                videoTarget.innerHTML = `<div class="video-wrapper"><iframe src="${processedUrl}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>`;
            } else {
                // 🔥 ADDED "volume" OVERRIDE FOR LOCAL VIDEOS 🔥
                videoTarget.innerHTML = `<div class="video-wrapper"><video id="local-vid" src="${processedUrl}" controls playsinline autoplay></video></div>`;
                setTimeout(() => {
                    let vid = document.getElementById('local-vid');
                    if(vid) {
                        vid.muted = false;
                        vid.volume = 1.0;
                    }
                }, 100);
            }
        }

        function triggerVictory() {
            document.getElementById('track-correct').value = trackCorrect;
            document.getElementById('track-incorrect').value = trackIncorrect;
            document.getElementById('track-wrong').value = JSON.stringify(trackWrongList);

            document.getElementById('success-overlay').classList.remove('hidden');
            document.getElementById('success-overlay').classList.add('flex');
            
            // Re-enable website music
            try { window.parent.postMessage('playMusic', '*'); } catch(e) {}
        }

        function loadQuestion() {
            if (currentIndex >= questions.length) {
                setTimeout(() => { triggerVictory(); }, 500);
                return;
            }

            const currentQ = questions[currentIndex];
            
            let safeQ = currentQ.question.replace(/"/g, '&quot;');
            questionEl.setAttribute('data-original', safeQ);
            questionEl.classList.add('dynamic-translation');
            
            if (currentLang === 'ms') {
                questionEl.innerText = "Translating...";
            } else {
                questionEl.innerText = currentQ.question;
            }

            if (currentQ.video_url && currentQ.video_url.trim() !== '') {
                currentVideoUrl = currentQ.video_url;
            }
            renderVideo(currentVideoUrl);

            roundEl.innerText = currentIndex + 1;
            totalEl.innerText = questions.length;
            
            feedbackEl.style.opacity = "0";
            optionsEl.innerHTML = '';
            isProcessing = false;

            const optionLetters = ['A', 'B', 'C', 'D'];
            
            optionLetters.forEach(letter => {
                if (currentQ.options && currentQ.options[letter] && currentQ.options[letter].trim() !== '') {
                    const btn = document.createElement('button');
                    btn.className = 'option-btn';
                    btn.id = `opt-${letter}`;
                    
                    let safeOpt = currentQ.options[letter].replace(/"/g, '&quot;');
                    btn.innerHTML = `<div class="option-letter">${letter}</div><span class="flex-1 dynamic-translation" data-original="${safeOpt}">${currentQ.options[letter]}</span>`;
                    
                    btn.onclick = () => submitAnswer(letter, currentQ.correct_option);
                    optionsEl.appendChild(btn);
                }
            });

            processDynamicTranslations(currentLang);
        }

        function submitAnswer(selectedLetter, correctLetter) {
    if (isProcessing) return;
    isProcessing = true;
    playClick();

    const currentQ = questions[currentIndex];
    const allBtns = document.querySelectorAll('.option-btn');
    allBtns.forEach(b => b.disabled = true);

    const selectedBtn = document.getElementById(`opt-${selectedLetter}`);

    if (selectedLetter === correctLetter) {
        trackCorrect++;
        if(sfxRight) { sfxRight.currentTime = 0; sfxRight.play().catch(()=>{}); }
        
        selectedBtn.classList.add('correct');
        
        feedbackEl.innerText = currentLang === 'en' ? `[VALID] QUERY RESOLVED` : `[SAH] PERTANYAAN DISELESAIKAN`;
        feedbackEl.className = "text-sm font-bold uppercase tracking-widest mt-6 text-emerald-400 opacity-100 transition-opacity drop-shadow-[0_0_5px_rgba(16,185,129,0.8)] text-center h-5";

        setTimeout(() => {
            currentIndex++;
            loadQuestion();
        }, 1500);

    } else {
        trackIncorrect++;
        trackWrongList.push(`Q: ${currentQ.question} | Guessed: ${currentQ.options[selectedLetter]}`);
        
        if(sfxWrong) { sfxWrong.currentTime = 0; sfxWrong.play().catch(()=>{}); }
        
        selectedBtn.classList.add('wrong');
        
        const correctBtn = document.getElementById(`opt-${correctLetter}`);
        if(correctBtn) correctBtn.classList.add('correct');
        
        strikes++;
        updateStrikes();
        
        feedbackEl.innerText = currentLang === 'en' ? `[ERROR] INCORRECT IDENTIFICATION` : `[RALAT] PENGENALPASTIAN TIDAK TEPAT`;
        feedbackEl.className = "text-sm font-bold uppercase tracking-widest mt-6 text-red-500 opacity-100 transition-opacity drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] text-center h-5";
        
        document.getElementById('game-container').classList.add('shake');
        setTimeout(() => document.getElementById('game-container').classList.remove('shake'), 500);

        if (strikes >= maxStrikes) {
            setTimeout(() => {
                document.getElementById('fail-overlay').classList.remove('hidden');
                document.getElementById('fail-overlay').classList.add('flex');
                try { window.parent.postMessage('playMusic', '*'); } catch(e) {}
                
                // 🔥 SILENT ARCADE LOGGER TRIGGERED HERE 🔥
                logArcadeFailure();
                
            }, 1500);
        } else {
            setTimeout(() => {
                currentIndex++;
                loadQuestion();
            }, 2000);
        }
    }
}

        function restartGame() {
            playClick();
            document.getElementById('fail-overlay').classList.add('hidden');
            document.getElementById('fail-overlay').classList.remove('flex');
            
            currentIndex = 0;
            strikes = 0;
            trackCorrect = 0; 
            trackIncorrect = 0; 
            trackWrongList = []; 
            
            updateStrikes();
            
            currentVideoUrl = gameData.video_url || null;
            lastRenderedUrl = null; 

            loadQuestion();
        }

        window.addEventListener('DOMContentLoaded', () => {
            applyLanguage(currentLang);
            
            if(questions.length > 0) {
                loadQuestion();
            } else {
                questionEl.innerText = "ERROR: NO QUERIES FOUND IN MODULE.";
                questionEl.classList.add('text-red-500');
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